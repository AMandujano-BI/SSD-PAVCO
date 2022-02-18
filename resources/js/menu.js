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
      icon: 'run',
      itemRoute: 'run.index'
      // updateMark: true
    },
    {
      label: 'Maintenance',
      icon: 'Maintenance',
      menu: [
        {
          label: 'Users',
          href: '/user',
          icon: 'Users',
          itemRoute: 'user.index'
        },
        {
          label: 'Company',
          href: '/company',
          icon: 'Company',
          itemRoute: 'company.index'
        },
        {
          label: 'Chemical',
          href: '/chemical',
          icon: 'Chemical',
          itemRoute: 'chemical.index'
        },

      ]
    },
    {
      // to: '/run',
      href: '/report',
      label: 'Reports',
      icon: 'Reports',
      itemRoute: 'report.index',
      updateMark: true
    },
    {
      label: 'Daily Hours',
      href: '/daily-hours',
      icon: 'DailyHours',
      itemRoute: 'dailyHours.index'
    }
  ],

]
