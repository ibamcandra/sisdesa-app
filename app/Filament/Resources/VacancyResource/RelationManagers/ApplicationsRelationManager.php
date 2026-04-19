<?php

namespace App\Filament\Resources\VacancyResource\RelationManagers;

use App\Models\JobApplication;
use App\Models\WhatsappConfig;
use App\Services\WhatsappService;
use App\Mail\InterviewNotification;
use App\Mail\ApplicationAcceptedNotification;
use App\Mail\ApplicationRejectedNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ApplicationsRelationManager extends RelationManager
{
    protected static string $relationship = 'applications';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('applicantProfile.name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('applicantProfile.name')
                    ->label('Nama Pelamar')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tgl Melamar')
                    ->dateTime('d M Y H:i'),

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
                //
            ])
            ->headerActions([
                // No create action
            ])
            ->actions([
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
                    ])
                    ->action(function (JobApplication $record, array $data) {
                        $newStatus = $data['status'];
                        
                        $updateData = ['status' => $newStatus];
                        
                        if ($newStatus === 'Interview') {
                            $updateData['interview_date'] = $data['interview_date'];
                            $updateData['interview_time'] = $data['interview_time'];
                            $updateData['interview_location'] = $data['interview_location'];
                        }

                        $record->update($updateData);

                        if (in_array($newStatus, ['Interview', 'Diterima', 'Ditolak'])) {
                            $this->sendNotifications($record, $newStatus, $data);
                        }
                    }),

                Action::make('view_cv')
                    ->label('CV')
                    ->icon('heroicon-o-document-text')
                    ->url(fn (JobApplication $record): string => route('admin.applicant.cv', $record->applicant_profile_id))
                    ->openUrlInNewTab(),

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
                                $record->update($updateData);

                                if (in_array($newStatus, ['Interview', 'Diterima', 'Ditolak'])) {
                                    $this->sendNotifications($record, $newStatus, $data);
                                }
                            }
                            \Filament\Notifications\Notification::make()
                                ->title('Status ' . count($records) . ' pelamar berhasil diperbarui')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function sendNotifications(JobApplication $application, string $status, array $data)
    {
        $applicant = $application->applicantProfile;
        $vacancy = $application->vacancy;
        $name = $applicant->name;
        $jobTitle = $vacancy->title;

        $message = "";
        
        if ($status === 'Interview') {
            $date = \Carbon\Carbon::parse($data['interview_date'])->format('d M Y');
            $time = $data['interview_time'];
            $loc = $data['interview_location'];
            $message = "Halo *$name*,\n\nSelamat! Lamaran Anda untuk posisi *$jobTitle* telah terpilih untuk tahap *Interview*.\n\nDetail Pelaksanaan:\n📅 Tanggal: $date\n⏰ Jam: $time\n📍 Tempat: $loc\n\nMohon hadir tepat waktu. Terima kasih.";
            Mail::to($applicant->email)->send(new InterviewNotification($application, $data));
        } 
        elseif ($status === 'Diterima') {
            $message = "Halo *$name*,\n\nSelamat! Anda dinyatakan *DITERIMA* untuk posisi *$jobTitle*. Tim kami akan segera menghubungi Anda untuk proses selanjutnya.\n\nTeria kasih.";
            Mail::to($applicant->email)->send(new ApplicationAcceptedNotification($application));
        } 
        elseif ($status === 'Ditolak') {
            $message = "Halo *$name*,\n\nTerima kasih telah melamar posisi *$jobTitle*. Saat ini kami belum bisa melanjutkan lamaran Anda ke tahap berikutnya.\n\nTetap semangat!";
            Mail::to($applicant->email)->send(new ApplicationRejectedNotification($application));
        }

        if (!empty($applicant->phone)) {
            WhatsappService::sendMessage($applicant->phone, $message);
        }
    }
}
