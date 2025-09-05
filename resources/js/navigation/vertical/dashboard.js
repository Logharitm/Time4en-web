export default [
  {
    title: 'Dashboards',
    icon: { icon: 'tabler-smart-home' },
    to: 'home-dashboard',
  },
  {
    title: 'المستخدمين',
    icon: { icon: 'tabler-user' },
    children: [
      { title: 'List', to: { name: 'admin-users' } },
    ],
  },
  {
    title: 'الادمن',
    icon: { icon: 'tabler-user-star' },
    children: [
      { title: 'List', to: { name: 'admin-admins' } },
      { title: 'Add', to: { name: 'admin-admins-add' } },
    ],
  },
  {
    title: 'المجلدات',
    icon: { icon: 'tabler-folders' },
    children: [
      { title: 'List', to: { name: 'admin-folders' } },
      { title: 'Add', to: { name: 'admin-folders-add' } },
    ],
  },
  {
    title: 'الكلمات',
    icon: { icon: 'tabler-file-word' },
    children: [
      { title: 'List', to: { name: 'admin-words' } },
      { title: 'Add', to: { name: 'admin-words-add' } },
    ],
  },
  {
    title: 'الاختبارات',
    icon: { icon: 'tabler-shield-question' },
    children: [
      { title: 'List', to: { name: 'admin-words' } },
      { title: 'Add', to: { name: 'admin-words-add' } },
    ],
  },
  {
    title: 'باقات الاشتراك',
    icon: { icon: 'tabler-plant-2' },
    children: [
      { title: 'List', to: { name: 'admin-plans' } },
      { title: 'Add', to: { name: 'admin-plans-add' } },
    ],
  },
  {
    title: 'الاشتراكات',
    icon: { icon: 'tabler-paywall' },
    children: [
      { title: 'List', to: { name: 'admin-plans' } },
      { title: 'Add', to: { name: 'admin-plans-add' } },
    ],
  },
  {
    title: 'المدفوعات',
    icon: { icon: 'tabler-brand-cashapp' },
    children: [
      { title: 'List', to: { name: 'admin-plans' } },
      { title: 'Add', to: { name: 'admin-plans-add' } },
    ],
  },
  {
    title: 'الرسائل الواردة',
    icon: { icon: 'tabler-brand-messenger' },
    children: [
      { title: 'List', to: { name: 'admin-plans' } },
      { title: 'Add', to: { name: 'admin-plans-add' } },
    ],
  },

  {
    title: 'الشروط والاحكام',
    icon: { icon: 'tabler-gavel' },
    children: [
      { title: 'List', to: { name: 'admin-plans' } },
      { title: 'Add', to: { name: 'admin-plans-add' } },
    ],
  },

  {
    title: 'بيانات الاتصال',
    icon: { icon: 'tabler-phone' },
    children: [
      { title: 'List', to: { name: 'admin-plans' } },
      { title: 'Add', to: { name: 'admin-plans-add' } },
    ],
  },
  {
    title: 'اسألة واجوبة',
    icon: { icon: 'tabler-help-hexagon' },
    children: [
      { title: 'List', to: { name: 'admin-plans' } },
      { title: 'Add', to: { name: 'admin-plans-add' } },
    ],
  },


]
