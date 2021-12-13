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
      itemRoute:'run.index'
      // updateMark: true
    },
    {
      label: 'Maintenance',
      icon:IconMaintenance,
      menu: [
        {
          label: 'Users',
          href: '/chemical',
          icon:IconUser,
          itemRoute:'chemical.index'
        },
        {
          label: 'Company',
          href: '/company',
          icon:IconCompany,
          itemRoute:'company.index'
        },
        {
          label: 'Chemical',
          href: '/chemical',
          icon:IconChemical,
          itemRoute:'chemical.index'
        }
      ]
    },
    {
      // to: '/run',
      href: '/run',
      label: 'Reports',
      icon: IconReport,
      itemRoute:'chemical.index',
      updateMark: true
    },
  ],

]
