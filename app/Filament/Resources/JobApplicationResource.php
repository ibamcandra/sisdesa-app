<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobApplicationResource\Pages;
use App\Models\JobApplication;
use App\Models\WhatsappConfig;
use App\Jobs\SendWhatsappNotification;
use App\Services\WhatsappService;
use App\Mail\InterviewNotification;
use App\Mail\ApplicationAcceptedNotification;
use App\Mail\ApplicationRejectedNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class JobApplicationResource extends Resource
{
    protected static ?string $model = JobApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Lowongan Pekerjaan';
    protected static ?string $navigationLabel = 'Lamaran Masuk';
    protected static ?string $pluralLabel = 'Lamaran Masuk';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        if (Auth::user()->role === 'recruitment') {
            return $query->whereHas('vacancy', fn ($q) => $q->where('user_id', Auth::id()));
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Read-only form for viewing details
                Forms\Components\Section::make('Informasi Lamaran')
                    ->schema([
                        Forms\Components\Placeholder::make('vacancy_title')
                            ->label('Lowongan')
                            ->content(fn ($record) => $record->vacancy->title),
                        Forms\Components\Placeholder::make('applicant_name')
                            ->label('Nama Pelamar')
                            ->content(fn ($record) => $record->applicantProfile->name),
                        Forms\Components\Placeholder::make('status')
                            ->label('Status Saat Ini')
                            ->content(fn ($record) => $record->status),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vacancy.title')
                    ->label('Lowongan')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('applicantProfile.name')
                    ->label('Pelamar')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('matching_score')
                    ->label('Match Score')
                    ->formatStateUsing(function ($record, $state) {
                        if ($record->scoring_status === 'processing' || $record->scoring_status === 'pending') {
                            return '⏳ Progres...';
                        }
                        if ($record->scoring_status === 'failed') {
                            return '❌ Gagal';
                        }
                        return ($state ?? 0) . '%';
                    })
                    ->badge()
                    ->color(fn ($record, $state): string => match (true) {
                        $record->scoring_status === 'processing' || $record->scoring_status === 'pending' => 'info',
                        $record->scoring_status === 'failed' => 'danger',
                        (int) $state >= 80 => 'success',
                        (int) $state >= 50 => 'warning',
                        default => 'danger',
                    })
                    ->tooltip(fn ($record) => $record->matching_reason),

                TextColumn::make('interview_score')
                    ->label('Score Interview')
                    ->formatStateUsing(fn ($state) => $state ? str_repeat('⭐', $state) : '-')
                    ->color('warning')
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Tgl Bekerja')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tgl Melamar')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Terkirim' => 'gray',
                        'Review' => 'info',
                        'Interview' => 'warning',
                        'Diterima' => 'success',
                        'Ditolak' => 'danger',
                        default => 'gray',
                    })
                    ->action(
                        Action::make('viewInterview')
                            ->label('Detail Jadwal Interview')
                            ->modalHeading('Jadwal Interview')
                            ->modalSubmitAction(false)
                            ->visible(fn ($record) => $record->status === 'Interview')
                            ->mountUsing(fn (Forms\ComponentContainer $form, JobApplication $record) => $form->fill([
                                'interview_date' => $record->interview_date?->format('d M Y'),
                                'interview_time' => $record->interview_time,
                                'interview_location' => $record->interview_location,
                            ]))
                            ->form([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('interview_date')
                                            ->label('Tanggal')
                                            ->disabled(),
                                        Forms\Components\TextInput::make('interview_time')
                                            ->label('Jam')
                                            ->disabled(),
                                        Forms\Components\TextInput::make('interview_location')
                                            ->label('Tempat / Link')
                                            ->columnSpanFull()
                                            ->disabled(),
                                    ])
                            ])
                    ),
            ])
            ->filters([
                SelectFilter::make('vacancy_id')
                    ->label('Filter Lowongan')
                    ->relationship('vacancy', 'title', function (Builder $query) {
                        if (Auth::user()->role === 'recruitment') {
                            $query->where('user_id', Auth::id());
                        }
                    }),
                SelectFilter::make('status')
                    ->options([
                        'Terkirim' => 'Terkirim',
                        'Review' => 'Review',
                        'Interview' => 'Interview',
                        'Diterima' => 'Diterima',
                        'Ditolak' => 'Ditolak',
                    ]),
            ])
            ->defaultSort('matching_score', 'desc')
            ->actions([
                // Custom Action for Updating Status with Notifications
                Action::make('updateStatus')
                    ->label('Ubah Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->mountUsing(fn (Forms\ComponentContainer $form, JobApplication $record) => $form->fill([
                        'status' => $record->status,
                        'interview_date' => $record->interview_date,
                        'interview_time' => $record->interview_time,
                        'interview_location' => $record->interview_location,
                    ]))
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Status Baru')
                            ->options([
                                'Review' => 'Review',
                                'Interview' => 'Interview',
                                'Diterima' => 'Diterima',
                                'Ditolak' => 'Ditolak',
                            ])
                            ->required()
                            ->live(),
                        
                        // Conditional fields for Interview
                        Forms\Components\Section::make('Detail Interview')
                            ->schema([
                                Forms\Components\DatePicker::make('interview_date')
                                    ->label('Tanggal Interview')
                                    ->required(),
                                Forms\Components\TimePicker::make('interview_time')
                                    ->label('Jam Interview')
                                    ->required(),
                                Forms\Components\TextInput::make('interview_location')
                                    ->label('Tempat / Link Meeting')
                                    ->placeholder('Contoh: Kantor Pusat atau Link Google Meet')
                                    ->required(),
                            ])
                            ->visible(fn (Forms\Get $get) => $get('status') === 'Interview'),

                        // Conditional fields for Diterima
                        Forms\Components\Section::make('Detail Penerimaan')
                            ->schema([
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Tanggal Mulai Bekerja')
                                    ->required(),
                                Forms\Components\Textarea::make('onboarding_notes')
                                    ->label('Catatan / Persiapan')
                                    ->placeholder('Sebutkan dokumen yang harus dibawa, dll...')
                                    ->required(),
                            ])
                            ->visible(fn (Forms\Get $get) => $get('status') === 'Diterima'),
                    ])
                    ->action(function (JobApplication $record, array $data) {
                        $newStatus = $data['status'];
                        
                        $updateData = ['status' => $newStatus];
                        
                        if ($newStatus === 'Interview') {
                            $updateData['interview_date'] = $data['interview_date'];
                            $updateData['interview_time'] = $data['interview_time'];
                            $updateData['interview_location'] = $data['interview_location'];
                        }

                        if ($newStatus === 'Diterima') {
                            $updateData['start_date'] = $data['start_date'];
                            $updateData['onboarding_notes'] = $data['onboarding_notes'];
                        }

                        $record->update($updateData);

                        // Notification Logic
                        if (in_array($newStatus, ['Interview', 'Diterima', 'Ditolak'])) {
                            self::sendNotifications($record, $newStatus, $data);
                        }
                    }),

                Action::make('inputInterviewResult')
                    ->label('Input Hasil Interview')
                    ->icon('heroicon-o-star')
                    ->color('success')
                    ->modalHeading(fn ($record) => in_array($record->status, ['Diterima', 'Ditolak']) ? 'Hasil Penilaian Interview (Read-only)' : 'Penilaian Interview')
                    ->modalSubmitAction(fn ($record) => in_array($record->status, ['Diterima', 'Ditolak']) ? false : null)
                    ->mountUsing(fn (Forms\ComponentContainer $form, JobApplication $record) => $form->fill([
                        'interview_score' => $record->interview_score,
                        'interview_notes' => $record->interview_notes,
                    ]))
                    ->form([
                        Forms\Components\Select::make('interview_score')
                            ->label('Rating Interview')
                            ->options([
                                1 => '⭐ (Buruk)',
                                2 => '⭐⭐ (Cukup)',
                                3 => '⭐⭐⭐ (Baik)',
                                4 => '⭐⭐⭐⭐ (Sangat Baik)',
                                5 => '⭐⭐⭐⭐⭐ (Luar Biasa)',
                            ])
                            ->required()
                            ->disabled(fn ($record) => in_array($record->status, ['Diterima', 'Ditolak'])),
                        Forms\Components\Textarea::make('interview_notes')
                            ->label('Catatan Interview')
                            ->placeholder('Masukkan catatan hasil interview di sini...')
                            ->rows(5)
                            ->disabled(fn ($record) => in_array($record->status, ['Diterima', 'Ditolak'])),
                    ])
                    ->action(function (JobApplication $record, array $data) {
                        $record->update($data);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Hasil interview berhasil disimpan')
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => in_array($record->status, ['Interview', 'Diterima', 'Ditolak'])),

                Action::make('view_cv')
                    ->label('CV')
                    ->icon('heroicon-o-document-text')
                    ->url(fn (JobApplication $record): string => route('admin.applicant.cv', $record->applicant_profile_id))
                    ->openUrlInNewTab(),

                Action::make('viewAIReason')
                    ->label('Analisis AI')
                    ->icon('heroicon-o-cpu-chip')
                    ->color('info')
                    ->modalHeading('Analisis Kecocokan AI')
                    ->modalSubmitAction(false)
                    ->visible(fn ($record) => $record->scoring_status === 'success')
                    ->form([
                        Forms\Components\Placeholder::make('ai_score')
                            ->label('Skor Kecocokan')
                            ->content(fn ($record) => $record->matching_score . '%'),
                        Forms\Components\Placeholder::make('ai_reason')
                            ->label('Analisis AI')
                            ->content(fn ($record) => $record->matching_reason),
                    ]),

                Action::make('retryScoring')
                    ->label('Retry AI')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Ulangi Analisis AI?')
                    ->modalDescription('Ini akan mengirim ulang data pelamar ke AI Gemini untuk dianalisis kembali.')
                    ->action(function (JobApplication $record) {
                        $record->update([
                            'scoring_status' => 'pending',
                            'matching_reason' => 'Sedang dijadwalkan ulang...'
                        ]);
                        \App\Jobs\AnalyzeJobApplicationScore::dispatch($record);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Analisis AI telah dijadwalkan ulang')
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => in_array($record->scoring_status, ['failed', 'processing', 'pending'])),

                Action::make('download_cv')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (JobApplication $record): ?string => $record->applicantProfile->cv_file ? Storage::url($record->applicantProfile->cv_file) : null)
                    ->openUrlInNewTab()
                    ->visible(fn (JobApplication $record): bool => !empty($record->applicantProfile->cv_file)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulkUpdateStatus')
                        ->label('Ubah Status Massal')
                        ->icon('heroicon-o-arrow-path')
                        ->color('info')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Status Baru')
                                ->options([
                                    'Review' => 'Review',
                                    'Interview' => 'Interview',
                                    'Diterima' => 'Diterima',
                                    'Ditolak' => 'Ditolak',
                                ])
                                ->required()
                                ->live(),
                            
                            Forms\Components\Section::make('Detail Interview')
                                ->schema([
                                    Forms\Components\DatePicker::make('interview_date')
                                        ->label('Tanggal Interview')
                                        ->required(),
                                    Forms\Components\TimePicker::make('interview_time')
                                        ->label('Jam Interview')
                                        ->required(),
                                    Forms\Components\TextInput::make('interview_location')
                                        ->label('Tempat / Link Meeting')
                                        ->required(),
                                ])
                                ->visible(fn (Forms\Get $get) => $get('status') === 'Interview'),

                            Forms\Components\Section::make('Detail Penerimaan')
                                ->schema([
                                    Forms\Components\DatePicker::make('start_date')
                                        ->label('Tanggal Mulai Bekerja')
                                        ->required(),
                                    Forms\Components\Textarea::make('onboarding_notes')
                                        ->label('Catatan / Persiapan')
                                        ->required(),
                                ])
                                ->visible(fn (Forms\Get $get) => $get('status') === 'Diterima'),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data) {
                            $newStatus = $data['status'];
                            
                            foreach ($records as $record) {
                                $updateData = ['status' => $newStatus];
                                
                                if ($newStatus === 'Interview') {
                                    $updateData['interview_date'] = $data['interview_date'];
                                    $updateData['interview_time'] = $data['interview_time'];
                                    $updateData['interview_location'] = $data['interview_location'];
                                }

                                if ($newStatus === 'Diterima') {
                                    $updateData['start_date'] = $data['start_date'];
                                    $updateData['onboarding_notes'] = $data['onboarding_notes'];
                                }

                                $record->update($updateData);

                                // Notification Logic
                                if (in_array($newStatus, ['Interview', 'Diterima', 'Ditolak'])) {
                                    self::sendNotifications($record, $newStatus, $data);
                                }
                            }

                            \Filament\Notifications\Notification::make()
                                ->title('Status ' . count($records) . ' pelamar berhasil diperbarui')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['vacancy.skills', 'applicantProfile.skills']));
    }

    protected static function sendNotifications(JobApplication $application, string $status, array $data)
    {
        $applicant = $application->applicantProfile;
        $vacancy = $application->vacancy;
        $name = $applicant->name;
        $jobTitle = $vacancy->title;

        $message = "";
        
        if ($status === 'Interview') {
            $date = $data['interview_date'];
            $time = $data['interview_time'];
            $loc = $data['interview_location'];
            
            $message = "Halo *$name*,\n\nSelamat! Lamaran Anda untuk posisi *$jobTitle* di Karang Taruna Desa Campaka telah terpilih untuk tahap *Interview*.\n\nDetail Pelaksanaan:\n📅 Tanggal: $date\n⏰ Jam: $time\n📍 Tempat: $loc\n\nMohon hadir tepat waktu. Terima kasih.";
            
            // Send Email
            Mail::to($applicant->email)->send(new InterviewNotification($application, $data));
        } 
        elseif ($status === 'Diterima') {
            $startDate = \Carbon\Carbon::parse($data['start_date'])->format('d M Y');
            $notes = $data['onboarding_notes'];
            
            $message = "Halo *$name*,\n\nSelamat! Anda dinyatakan *DITERIMA* untuk posisi *$jobTitle* di Karang Taruna Desa Campaka.\n\nDetail Bergabung:\n📅 Tanggal Mulai: $startDate\n📝 Catatan/Persiapan: $notes\n\nTim kami akan segera menghubungi Anda untuk proses selanjutnya. Terima kasih.";
            
            Mail::to($applicant->email)->send(new ApplicationAcceptedNotification($application));
        } 
        elseif ($status === 'Ditolak') {
            $message = "Halo *$name*,\n\nTerima kasih telah melamar posisi *$jobTitle* di Karang Taruna Desa Campaka. Saat ini kami belum bisa melanjutkan lamaran Anda ke tahap berikutnya.\n\nTetap semangat dan sukses untuk karir Anda ke depan.";
            
            Mail::to($applicant->email)->send(new ApplicationRejectedNotification($application));
        }

        // Send WhatsApp via Queue
        if (!empty($applicant->phone)) {
            SendWhatsappNotification::dispatch($applicant->phone, $message);
        }
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobApplications::route('/'),
        ];
    }
}
