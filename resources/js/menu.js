import IconCompany from './assets/Icons/IconCompany/list.png'
import IconChemical from './assets/Icons/IconChemical/chemical.png'
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
      itemRoute: 'run.index'
      // updateMark: true
    },
    {
      label: 'Maintenance',
      icon: IconMaintenance,
      menu: [
        {
          label: 'Users',
          href: '/user',
          icon: IconUser,
          itemRoute: 'user.index'
        },
        {
          label: 'Company',
          href: '/company',
          icon: IconCompany,
          itemRoute: 'company.index'
        },
        {
          label: 'Chemical',
          href: '/chemical',
          icon: IconChemical,
          itemRoute: 'chemical.index'
        }
      ]
    },
    {
      // to: '/run',
      href: '/report',
      label: 'Reports',
      icon: IconReport,
      itemRoute: 'report.index',
      updateMark: true
    },
  ],

]
