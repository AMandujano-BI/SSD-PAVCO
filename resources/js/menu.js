import IconCompany from './assets/Icons/IconCompany/list.png'
import IconChemical from './assets/Icons/iconChemical/chemical.png'
import IconRun from './assets/Icons/IconRun/run.png'
import IconMaintenance from './assets/Icons/IconMaintenance/gear.png'
import IconUser from './assets/Icons/IconUserLeftBar/user.png'
import IconReport from './assets/Icons/iconReports/report.png'
export default [
  'General',
  [
    {
      // to: '/run',
      href: '/run',
      label: 'Runs',
      icon: IconRun,
      // updateMark: true
    },
    {
      label: 'Maintenance',
      icon:IconMaintenance,
      menu: [
        {
          label: 'Users',
          href: '/chemical',
          icon:IconUser
        },
        {
          label: 'Company',
          href: '/chemical',
          icon:IconCompany
        },
        {
          label: 'Chemical',
          href: '/chemical',
          icon:IconChemical
        }
      ]
    },
    {
      // to: '/run',
      href: '/run',
      label: 'Reports',
      icon: IconReport,
      updateMark: true
    },
  ],

]
