

import SettingSystem from '../Pages/Settings/System/Index.vue';
import SystemEmail from '../Pages/Settings/System/Email.vue';
import SystemGeneral from '../Pages/Settings/System/General.vue';
import SystemLanguage from '../Pages/Settings/System/Languages/Index.vue';
import SystemLanguageForm from '../Pages/Settings/System/Languages/Index.vue';

import User from '../Pages/Settings/User/Index.vue';
import UserForm from '../Pages/Settings/User/Form.vue';


import Branch from '../Pages/Settings/Branches.vue';

import Permission from '../Pages/Settings/Permission/Index.vue';
import PermissionForm from '../Pages/Settings/Permission/Form.vue';


export default [
	{
        path: '/settings/',
        children: [
          {
            path: 'system',
            name: 'settings.system',
            component: SettingSystem,
            children : [
                {
                    path: '',
                    name: 'settings.system.general',
                    component: SystemGeneral
                },
                {
                    path: 'email',
                    name: 'settings.system.email',
                    component: SystemEmail
                },
                {
                  path: 'language',
                  name: 'settings.system.language',
                  children : [
                      {
                        path: '',
                        name : 'settings.system.language.index',
                        component: SystemLanguage
                      },
                      {
                        path: 'create',
                        name : 'settings.system.language.create',
                        component: SystemLanguageForm
                      },
                      {
                        path: ':id/edit',
                        name : 'settings.system.languages.edit',
                        component: SystemLanguageForm
                      },
                  ]
                },
            ]
          },
          {
            path: 'user',
            children : [
                {
                  path: '',
                  name : 'user',
                  component: User
                },
                {
                  path: 'create',
                  name : 'user.create',
                  component: UserForm
                },
                {
                  path: ':id/edit',
                  name : 'user.edit',
                  component: UserForm
                },
            ]
          },
          {
            path: 'permissions',
            children : [
                {
                  path: '',
                  name : 'permissions',
                  component: Permission
                },
                {
                  path: 'create',
                  name : 'permissions.create',
                  component: PermissionForm
                },
                {
                  path: ':id/edit',
                  name : 'permissions.edit',
                  component: PermissionForm
                },
            ]
          },
          {
            path: 'Branch',
            component: Branch,
            meta: {
                requireAuth: true,
                menuParent: "setting",
                menuKey: "payment_in",
                permission: "branch_view",
                paymentType: "in",
            }
          },
        ]

	}
]