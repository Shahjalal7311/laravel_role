<?php

namespace App;

class Permission extends \Spatie\Permission\Models\Permission
{

    public static function defaultPermissions()
    {
        return [
            'view_users',
            'add_users',
            'edit_users',
            'delete_users',
            'import_users',
            'print_users',

            'view_roles',
            'add_roles',
            'edit_roles',
            'delete_roles',
            'import_roles',
            'print_roles',

            'view_posts',
            'add_posts',
            'edit_posts',
            'delete_posts',
            'import_posts',
            'print_posts',

            'view_articals',
            'add_articals',
            'edit_articals',
            'delete_articals',
            'import_articals',
            'print_articals',
        ];
    }
}
