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
      href: '/run',
      label: 'Runs',
      icon: 'run',
      itemRoute: 'run.index'
    },
    {
      href: '/report',
      label: 'Reports',
      icon: 'Reports',
      itemRoute: 'report.index',
      updateMark: true
    },
  ],

]
