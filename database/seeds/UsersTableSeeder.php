<?php

use Illuminate\Database\Seeder;
use App\Eloquent\User;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Eloquent\Group;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $json = Storage::disk('local')->get('users.json');

        $data = json_decode($json, true);

        foreach ($data as $obj){
            $listGroup = $this->getGroupDetail($obj);

            $listWorkspace = $obj['workspaces'];

            $birthday = Carbon::parse($obj['birthday'])->toDateString();

            $user = User::create(array(
                'email' => $obj['email'],
                'name' => $obj['name'],
                'code' => $obj['employee_code'],
                'birthday' => $birthday,
                'location' => json_encode($listWorkspace),
                'avatar' => $obj['avatar'],
                'mission' => $obj['position'],
                'gender' => array_get(config('model.user.gender'), $obj['gender']),
                'status' => array_get(config('model.user.status'), $obj['status']),
                'token_verification' => str_random(60),
            ));

            $user->groups()->syncWithoutDetaching($listGroup);
            $user->workspaces()->syncWithoutDetaching($listWorkspace);
            $user->groups()->updateExistingPivot(last($listGroup), ['manager' => true]);
        }
    }

    public function getGroupDetail($userFromAuthServer)
    {
        $listGroup = [];
        //Get Group Detail From Auth Sever
        $listGroupInfo = $userFromAuthServer['groups'];
        foreach ($listGroupInfo as $groupInfo) {
            //Create Group From Detail
            $parentGroupInfo = $groupInfo['parent_path'];
            $parentGroupCode = null;
            //Parent Group Of Current User Group
            foreach ($parentGroupInfo as $group) {
                $group = app(Group::class)::updateOrCreate(
                    [
                        'code' => $group['id'],
                    ],
                    [
                        'name' => $group['name'],
                        'parent_id' => $parentGroupCode,
                        'type' => Group::DEFAULT_GROUP,
                    ]
                );
                $parentGroupCode = $group->code;
            }
            //Current User Group
            $groupUser = app(Group::class)::updateOrCreate(
                [
                    'code' => $groupInfo['id'],
                ],
                [
                    'name' => $groupInfo['name'],
                    'parent_id' => $parentGroupCode,
                    'type' => Group::DEFAULT_GROUP,
                ]
            );

            $listGroup[] = $groupUser->id;
        }

        $listGroup[] = $this->createGroupWithLoginUser($userFromAuthServer);

        return $listGroup;
    }

    public function createGroupWithLoginUser($userFromAuthServer)
    {
        $groupUser = app(Group::class)::updateOrCreate(
            [
                'code' => $userFromAuthServer['employee_code'],
            ],
            [
                'name' => $userFromAuthServer['name'],
            ]
        );

        return $groupUser->id;
    }
}
