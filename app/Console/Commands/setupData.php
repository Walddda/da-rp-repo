<?php

namespace App\Console\Commands;

use App\Models\Cover;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class setupData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up required data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $this->line('Display this on the screen');
        $defCover = new Cover;
        $defCover->name = time() . '_placeholder.jpg';
        $defCover->cover_path = '/storage/covers/placeholder.jpg';
        $defCover->save();

        $user = new User;
        $user->first_name = 'admin';
        $user->last_name = 'admin';
        $user->email = 'beatchainbeatchain@gmail.com';
        $user->email_verified_at = Carbon::now()->toDateTimeString();
        $user->username = 'admin';
        $user->password = Hash::make('VwW2KPh^fA4oy@8yTBsWW');
        $user->save();
        // $this->line('Display this on the screen2');

        $this->info('The command was successful!');
        // $this->error('Something went wrong!');

        return Command::SUCCESS;
    }
}
