export default [
  {
    title: 'Dashboards',
    icon: { icon: 'tabler-smart-home' },
    to: 'home-dashboard',
  },
  {
    title: 'المستخدمين',
    icon: { icon: 'tabler-user' },
    to: { name: 'admin-users' },
  },
  {
    title: 'الادمن',
    icon: { icon: 'tabler-user-star' },
    to: { name: 'admin-admins' },
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
    to: { name: 'admin-plans' },
  },
  {
    title: 'الاشتراكات',
    icon: { icon: 'tabler-paywall' },
    to: { name: 'admin-subscriptions' },
  },
  {
    title: 'المدفوعات',
    icon: { icon: 'tabler-brand-cashapp' },
    to: { name: 'admin-payments' },
  },
  {
    title: 'الرسائل الواردة',
    icon: { icon: 'tabler-brand-messenger' },
    to: { name: 'admin-messages' },
  },

  {
    title: 'الشروط والاحكام',
    icon: { icon: 'tabler-gavel' },
    to: { name: 'admin-policy' },
  },

  {
    title: 'بيانات الاتصال',
    icon: { icon: 'tabler-phone' },
    to: { name: 'admin-contact' },
  },
  {
    title: 'اسألة واجوبة',
    icon: { icon: 'tabler-help-hexagon' },
    to: { name: 'admin-faqs' },
  },


]
