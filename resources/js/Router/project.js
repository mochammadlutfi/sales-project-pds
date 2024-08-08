

import SalesIndex from '../Pages/Sales/Index.vue';
import SalesForm from '../Pages/Sales/Form.vue';

import ProjectIndex from '../Pages/Project/Index.vue';
import ProjectForm from '../Pages/Project/Form.vue';

export default [
    {
      path: '/sales/',
      children : [
          {
            path: '',
            name : 'sales.index',
            component: SalesIndex
          },
          {
            path: '/create',
            name : 'sales.index',
            component: SalesForm
          },
          {
            path: '/:id/edit',
            name : 'sales.edit',
            component: SalesForm
          },
      ]
    },
    {
      path: '/project',
      children : [
          {
            path: '/',
            name : 'project.index',
            component: ProjectIndex
          },
          {
            path: '/create',
            name : 'project.index',
            component: ProjectForm
          },
          {
            path: '/:id/edit',
            name : 'project.edit',
            component: ProjectForm
          },
      ]
    },
]