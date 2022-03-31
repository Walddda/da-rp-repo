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
        $defCover->name = time() . '_default_cover.png';
        $defCover->cover_path = '/storage/covers/Default-cover.png';
        $defCover->save();

        $defCover1 = new Cover;
        $defCover1->name = time() . '_default_cover_silver.png';
        $defCover1->cover_path = '/storage/covers/Default-cover_silver.png';
        $defCover1->save();

        $defCover2 = new Cover;
        $defCover2->name = time() . '_default_cover_gold.png';
        $defCover2->cover_path = '/storage/covers/Default-cover_gold.png';
        $defCover2->save();

        $defCover3 = new Cover;
        $defCover3->name = time() . '_default_cover_diamond.png';
        $defCover3->cover_path = '/storage/covers/Default-cover_diamond.png';
        $defCover3->save();

        $user = new User;
        $user->first_name = 'admin';
        $user->last_name = 'admin';
        $user->email = 'beatchainbeatchain@gmail.com';
        $user->email_verified_at = Carbon::now()->toDateTimeString();
        $user->username = 'admin';
        $user->password = Hash::make('VwW2KPh^fA4oy@8yTBsWW');
        $user->eth_address = '0x123bb88d5acf168ce0a30863afef171689970cf8';
        $user->isadmin = 1;
        $user->save();
        // $this->line('Display this on the screen2');

        $this->info('The command was successful!');
        // $this->error('Something went wrong!');

        // $defCover = Cover::create([
        //     'name' => time() . '_default_cover.png',
        //     'cover_path' => '/storage/covers/Default-cover.png',
        // ]);
        // $defCover1 = Cover::create([
        //     'name' => time() . '_default_cover_silver.png',
        //     'cover_path' => '/storage/covers/Default-cover_silver.png',
        // ]);
        // $defCover2 = Cover::create([
        //     'name' => time() . '_default_cover_gold.png',
        //     'cover_path' => '/storage/covers/Default-cover_gold.png',
        // ]);
        // $defCover3 = Cover::create([
        //     'name' => time() . '_default_cover_diamond.png',
        //     'cover_path' => '/storage/covers/Default-cover_diamond.png',
        // ]);



        // $user = User::create([
        //     'first_name' => 'admin',
        //     'last_name' => 'admin',
        //     'email' => 'beatchainbeatchain@gmail.com',
        //     'email_verified_at' => Carbon::now()->toDateTimeString(),
        //     'username' => 'admin',
        //     'password' => Hash::make('VwW2KPh^fA4oy@8yTBsWW')
        // ]);
        // $user2 = User::create([
        //     'first_name' => 'test',
        //     'last_name' => 'user',
        //     'email' => 'samuel.g.anmeldung@gmail.com',
        //     'email_verified_at' => Carbon::now()->toDateTimeString(),
        //     'username' => 'adam',
        //     'password' => Hash::make('VwW2KPh^fA4oy@8yTBsWW')
        // ]);

        return Command::SUCCESS;
    }
}
