<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Eloquent\Workspace;

class WorkspacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = Storage::disk('local')->get('workspaces.json');

        $data = json_decode($json, true);

        foreach ($data as $obj){
            Workspace::create([
                'name' => $obj['name'],
                'description' => $obj['description'],
                'open_time' => $obj['open_time'],
                'close_time' => $obj['close_time'],
                'timezone' => $obj['timezone'],
            ]);
        }
    }
}
