export default [
  {
    title: 'Dashboards',
    icon: { icon: 'tabler-smart-home' },
    to: 'home-dashboard',
  },
  {
    title: 'Users',
    icon: { icon: 'tabler-user' },
    children: [
      { title: 'List', to: { name: 'admin-users' } },
      { title: 'Add', to: { name: 'admin-users-add' } },
    ],
  },
  {
    title: 'Admins',
    icon: { icon: 'tabler-user' },
    children: [
      { title: 'List', to: { name: 'admin-admins' } },
      { title: 'Add', to: { name: 'admin-admins-add' } },
    ],
  },
  {
    title: 'Folders',
    icon: { icon: 'tabler-user' },
    children: [
      { title: 'List', to: { name: 'admin-folders' } },
      { title: 'Add', to: { name: 'admin-folders-add' } },
    ],
  },
  {
    title: 'Words',
    icon: { icon: 'tabler-user' },
    children: [
      { title: 'List', to: { name: 'admin-words' } },
      { title: 'Add', to: { name: 'admin-words-add' } },
    ],
  },
  {
    title: 'Plans',
    icon: { icon: 'tabler-user' },
    children: [
      { title: 'List', to: { name: 'admin-plans' } },
      { title: 'Add', to: { name: 'admin-plans-add' } },
    ],
  },


]
