import React from 'react'

function Features() {
  return (
    <>
    <section id="features" className="py-24 clip-top-diagonal clip-bottom-diagonal" style={{ background: '#161f30' }}>
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
            <div key={i} className="rounded-xl p-8 shadow-lg transform transition duration-500 hover:rotate-1 hover:scale-105 hover:shadow-2xl" style={{ background: '#1a2238' }}>
              <i className={`fas ${f.icon} text-5xl ${f.color} mb-4`} />
              <h3 className="text-xl font-semibold mb-2">{f.title}</h3>
              <p className="text-blue-100">{f.desc}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
    </>
  )
}

export default Features