import { createRouter, createWebHistory } from 'vue-router'
import AuthMiddleware from './Middleware/AuthMiddleware'

import DashboardView from '../Pages/Dashboard.vue';


import ProfileIndex from '../Pages/Profile/Index.vue';
import ProfileEdit from '../Pages/Profile/Edit.vue';
import ProfilePassword from '../Pages/Profile/Password.vue';
import ProfileDevice from '../Pages/Profile/Device.vue';

import SettingRoutes from './settings';
// import ProjectRoutes from './project';
import Login from '../Pages/Auth/Login.vue';

import SalesIndex from '../Pages/Sales/Index.vue';
import SalesForm from '../Pages/Sales/Form.vue';

import ProjectIndex from '../Pages/Project/Index.vue';
import ProjectForm from '../Pages/Project/Form.vue';
import ProjectShow from '../Pages/Project/Show.vue';

import ActivityIndex from '../Pages/ProjectActivity/Index.vue';

const router = createRouter({
	history: createWebHistory('/'),
	routes: [
		{ name: 'login', path: '/', component: Login, meta: { layout: 'Guest' } },
		{
			path: '/dashboard',
			name: 'dashboard',
			component: DashboardView,
            meta : {
                requiredAuth : true,
                permission : 'dashboard_view',
            }
		},
        {
          path: '/profile/',
          name: 'profile',
          component: ProfileIndex,
          children : [
              {
                  path: '',
                  name: 'profile.edit',
                  component: ProfileEdit
              },
              {
                  path: 'password',
                  name: 'profile.password',
                  component: ProfilePassword
              },
              {
                  path: 'devices',
                  name: 'profile.device',
                  component: ProfileDevice
              },
          ]
        },
        {
            path: '/sales',
            children : [
                {
                  path: '',
                  name : 'sales',
                  component: SalesIndex
                },
                {
                  path: 'create',
                  name : 'sales.create',
                  component: SalesForm
                },
                {
                  path: ':id/edit',
                  name : 'sales.edit',
                  component: SalesForm
                },
            ]
        },
        {
          path: '/project',
          children : [
              {
                path: '',
                name : 'project',
                component: ProjectIndex
              },
              {
                path: 'create',
                name : 'project.create',
                component: ProjectForm
              },
              {
                path: ':id/edit',
                name : 'project.edit',
                component: ProjectForm
              },
              {
                path: ':id',
                name : 'project.show',
                component: ProjectShow
              },
          ]
        },
		{
			path: '/activity',
			name: 'activity',
			component: ActivityIndex,
            meta : {
                requiredAuth : true,
                permission : 'activity_view',
            }
        },
        ...SettingRoutes,
        // ...ProjectRoutes,
	],
})

router.beforeEach(AuthMiddleware);

export default router