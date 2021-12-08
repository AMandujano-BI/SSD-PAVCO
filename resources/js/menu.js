// import {
//     mdiAccountCircle,
//     mdiDesktopMac,
//     mdiGithub,
//     mdiLock,
//     mdiAlertCircle,
//     mdiMonitorShimmer,
//     mdiSquareEditOutline,
//     mdiTable,
//     mdiViewList,
//     mdiTelevisionGuide,
//     mdiResponsive
//   } from '@mdi/js'

export default [
  'General',
  [
    {
      // to: '/',
      href: "/dashboard",
      icon: '',
      label: 'Dashboard'
    },
    {
      // to: '/run',
      href: '/run',
      label: 'Runs',
      icon: '',
      updateMark: true
    },
    {
      // to: '/run',
      href: '/run',
      label: 'Daily Hours',
      icon: '',
      updateMark: true
    },
    {
      label: 'Maintenance',
      subLabel: 'Submenus Example',
      icon: '',
      menu: [
        {
          label: 'Users',
          href: '/chemical',
        },
        {
          label: 'Company',
          href: '/chemical',
        },
        {
          label: 'Chemical',
          href: '/chemical',
        }
      ]
    },
    {
      // to: '/run',
      href: '/run',
      label: 'Reports',
      icon: '',
      updateMark: true
    },
  ],

]
