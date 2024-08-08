/*
 * Main and demo navigation arrays
 */

export default {
    'main': [
      {
        name: 'dashboard',
        to: '/dashboard',
        icon: 'mgc_home_3_line',
        permission : 'dashboard.view'
      },
      {
        name: 'project.title',
        to: '/project',
        icon: 'mgc_suitcase_line',
        permission : 'project.view'
      },
      {
        name: 'project.activity',
        to: '/activity',
        icon: 'mgc_list_check_2_line',
        permission : 'project.activity.view'
      },
      {
        name: 'sales_person',
        to: '/sales',
        icon: 'mgc_group_line',
        permission : 'project.view'
      },
      {
        name: 'settings',
        icon: 'mgc_settings_4_line ',
        subActivePaths: '/settings/',
        permission : 'settings.system',
        sub: [
            {
              name: 'system',
              to: '/settings/system',
              permission : 'settings.system'
            },
            {
              name: 'user',
              to: '/settings/user',
              permission : 'user.view'
            },
            {
              name: 'role_permission',
              to: '/settings/permissions',
              permission: 'permission.view',
            },
            {
              name: 'branch',
              to: '/settings/branch',
              permission: 'branch.view',
            },
        ]
      },
    ],
  }
  