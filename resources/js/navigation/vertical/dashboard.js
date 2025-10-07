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
    to: { name: 'admin-folders' },
    
  },
  {
    title: 'الكلمات',
    icon: { icon: 'tabler-file-word' },
    to: { name: 'admin-words' },
    
  },
  {
    title: 'الاختبارات',
    icon: { icon: 'tabler-shield-question' },
    to: { name: 'admin-quizzes' },
    
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
  {
    title: 'الاشعارات',
    icon: { icon: 'tabler-bell' },
    
    children: [
      {
        title: 'اضافة اشعار',
        to: { name: 'admin-notifications-add' },
        icon: { icon: 'tabler-circle' },
      },
      {
        title: 'عرض الكل',
        to: { name: 'admin-notifications' },
        icon: { icon: 'tabler-circle' },
      },
    ],
  },


]
