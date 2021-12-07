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
        href: '/',
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
        // to: '/chemical',
        href: '/chemical',
        label: 'Chemicals',
        icon: '',
        updateMark: true
      },
      {
        label: 'Submenus',
        subLabel: 'Submenus Example',
        icon: '',
        menu: [
          {
            label: 'Sub-item One'
          },
          {
            label: 'Sub-item Two'
          }
        ]
      }
    ],
  
  ]
  