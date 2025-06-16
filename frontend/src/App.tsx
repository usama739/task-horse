import { SignedIn, SignedOut, SignInButton, UserButton } from '@clerk/clerk-react'
import './App.css'


function App() {
  return (
    <div className="text-white font-sans min-h-screen flex flex-col" style={{ background: '#0e1525' }}>
    {/* Header */}
    <header className="w-full px-6 py-4 flex justify-between items-center bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 shadow-lg">
      <img src="/images/logo4.png" alt="TaskHorse Logo" className="h-10 w-15" />
      <nav className="flex items-center gap-4">
        <a href="/login" className="px-4 py-2 rounded-md font-semibold bg-white text-blue-700 hover:bg-gray-100 transition shadow">
          Log in
        </a>
        <a href="/register" className="px-4 py-2 rounded-md font-semibold bg-blue-500 hover:bg-blue-600 transition text-white shadow">
          Register
        </a>
      </nav>
    </header>

    {/* Hero */}
    <section className="relative flex-1 flex items-center justify-center bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 py-24 px-6">
      <div className="max-w-3xl mx-auto text-center z-10">
        <h1 className="text-5xl md:text-6xl font-extrabold leading-tight mb-6 bg-gradient-to-r from-blue-400 via-cyan-400 to-green-300 bg-clip-text text-white drop-shadow-lg">
          Stay on Track. Get Things Done.
        </h1>
        <p className="text-lg md:text-2xl text-blue-100 mb-10 font-medium">
          A cloud‑powered task management tool built for teams who value speed, simplicity, and clarity
        </p>
        <a href="/register" className="inline-block bg-gradient-to-r from-cyan-400 to-blue-600 hover:from-blue-500 hover:to-blue-500 text-white font-bold py-4 px-5 rounded-lg shadow-lg text-lg transition mt-10">
          Start Free Trial
        </a>
        <div className="mt-8 flex flex-wrap justify-center gap-4">
          {[
            { icon: 'fa-users', label: 'Team Collaboration', iconColor: 'text-blue-300', textColor: 'text-blue-100' },
            { icon: 'fa-lock', label: 'Enterprise Security', iconColor: 'text-green-300', textColor: 'text-green-100' },
            { icon: 'fa-bolt', label: 'Lightning Fast', iconColor: 'text-yellow-300', textColor: 'text-yellow-100' },
          ].map((item, i) => (
            <div key={i} className="rounded-lg px-6 py-3 flex items-center gap-3 shadow" style={{ background: '#1a2238' }}>
              <i className={`fas ${item.icon} ${item.iconColor} text-2xl`} />
              <span className={`${item.textColor} font-semibold`}>{item.label}</span>
            </div>
          ))}
        </div>
      </div>
      <div className="absolute inset-0 pointer-events-none z-0">
        <div className="absolute inset-0 bg-gradient-to-br from-blue-900/60 via-blue-800/40 to-blue-700/20 blur-2xl" />
      </div>
    </section>

    {/* Features */}
    <section id="features" className="py-24" style={{ background: '#161f30' }}>
      <div className="container mx-auto px-6">
        <h2 className="text-3xl md:text-4xl font-bold text-center text-white">
          Features That Empower Your Team
        </h2>
        <div className="grid grid-cols-1 md:grid-cols-4 gap-12 text-center mt-10">
          {[
            { icon: 'fa-calendar-alt', color: 'text-blue-400', title: 'Smart Task Scheduling', desc: 'Plan your week with a calendar view, due dates, and reminders.' },
            { icon: 'fa-tasks', color: 'text-yellow-400', title: 'Timeline Visualization', desc: 'Track progress with an interactive timeline grouped by task date.' },
            { icon: 'fa-chart-line', color: 'text-green-400', title: 'Statistics & Insights', desc: 'Visual stats for pending, in‑progress, and completed tasks.' },
            { icon: 'fa-lock', color: 'text-green-400', title: 'Enterprise‑Grade Security', desc: 'All files are encrypted and stored in the AWS cloud, ensuring your data is always protected.' },
          ].map((f, i) => (
            <div key={i} className="rounded-xl p-8 shadow-lg hover:scale-105 transition" style={{ background: '#1a2238' }}>
              <i className={`fas ${f.icon} text-5xl ${f.color} mb-4`} />
              <h3 className="text-xl font-semibold mb-2">{f.title}</h3>
              <p className="text-blue-100">{f.desc}</p>
            </div>
          ))}
        </div>
      </div>
    </section>

    {/* Workflow */}
    <section className="py-24 bg-gradient-to-br from-[#1a2238] via-[#161f30] to-[#0e1525]">
      <div className="container mx-auto px-6">
        <h2 className="text-3xl md:text-4xl font-bold text-center text-white">How It Works</h2>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-12 mt-10">
          {[
            { icon: 'fa-user-plus', title: '1. Sign Up & Invite', desc: 'Create your account and invite your team. All user data is protected with enterprise‑grade security.' },
            { icon: 'fa-layer-group', title: '2. Organize & Assign', desc: 'Create projects, assign tasks, and upload files. Every file is encrypted and stored in the AWS cloud for maximum security.' },
            { icon: 'fa-rocket', title: '3. Track & Succeed', desc: 'Monitor progress, collaborate, and share files with confidence—your data is always safe with cloud‑based encryption.' },
          ].map((step, i) => (
            <div key={i} className="flex flex-col items-center">
              <div className="bg-blue-700 rounded-full p-5 mb-4 shadow-lg">
                <i className={`fas ${step.icon} text-3xl text-white`} />
              </div>
              <h4 className="text-lg font-bold mb-2">{step.title}</h4>
              <p className="text-blue-100 text-center">{step.desc}</p>
            </div>
          ))}
        </div>
      </div>
    </section>

    {/* Call To Action */}
    <section id="signup" className="py-24 bg-gradient-to-r from-blue-700 via-blue-600 to-cyan-500 text-center">
      <h3 className="text-3xl md:text-4xl font-bold mb-4 text-white">Ready to Supercharge Your Productivity?</h3>
      <p className="text-white text-lg mb-8">Join teams who get more done every day with TaskHorse SaaS.</p>
      <a href="/register" className="bg-white text-blue-700 font-bold px-5 py-4 rounded-lg hover:bg-gray-100 transition text-lg shadow-lg">
        Try It Free
      </a>
      <div className="mt-8 flex flex-wrap justify-center gap-6">
        {['No credit card required', 'Cancel anytime', 'Encrypted cloud file storage', '24/7 support'].map((item, i) => (
          <div key={i} className="flex items-center gap-2 text-blue-100">
            <i className="fas fa-check-circle text-green-400" /> {item}
          </div>
        ))}
      </div>
    </section>

    {/* Footer */}
    <footer className="py-8 text-center text-blue-200 text-sm border-t border-blue-900" style={{ background: '#161f30' }}>
      <div className="mb-2">
        {['Features', 'Pricing', 'Docs', 'Contact'].map((link, i) => (
          <a key={i} href="#" className="mx-2 hover:underline">{link}</a>
        ))}
      </div>
      <div className="flex flex-col items-center justify-center gap-2 mt-4">
        <span className="flex items-center gap-2 text-green-300">
          <i className="fas fa-cloud" />
          Secure AWS Cloud Storage – All files encrypted in transit and at rest
        </span>
        <span className="flex items-center gap-2 text-blue-200">
          <i className="fas fa-shield-alt" />
          Enterprise‑grade security for your team’s data
        </span>
      </div>
      <p className="mt-4">&copy; {new Date().getFullYear()} TaskHorse SaaS. All rights reserved.</p>
    </footer>
  </div>
  )
}

export default App
