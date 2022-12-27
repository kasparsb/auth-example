<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Auth;

/**
 * Test, lai saprastu kā darbojas Auth guard utt
 *
 * auth with default config
 *
 */
class AuthDefault extends Command
{
    protected $signature = 'auth:default';

    protected $description = 'Command description';

    public function handle()
    {
        $credentials = [
            'email' => 'kasparsb@hotmail.com',
            'password' => 'asdf',
        ];

        //$r = Auth::attempt($credentials);

        // šeit atgriež, ka nav metodes attempt
        //$r = Auth::guard('tablet')->attempt($credentials);

        $r = Auth::guard('admin')->attempt($credentials);

        //$r = Auth::guard('admin2')->attempt($credentials);
        //dd(Auth::guard('admin2')->user());



        dump($r);

        return Command::SUCCESS;
    }
}
