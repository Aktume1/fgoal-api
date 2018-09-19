<?php

use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = Storage::disk('local')->get('groups.json');

        $data = json_decode($json, true);

        $path = null;
        foreach ($data as $group) {
            if ($group['parent_path'] == null) {
                $path = null;
            } else {
                $path = implode('/', $group['parent_path']);
            }

            \App\Eloquent\Group::updateOrCreate(
                [
                    'code' => $group['id'],
                ],
                [
                    'parent_path' => $path,
                    'name' => $group['name'],
                ]
            );
        }
    }
}
