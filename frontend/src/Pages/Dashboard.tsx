import React from 'react';
import Header from '../Components/Header';

const Dashboard: React.FC = () => {
  return (
    <>

    <Header isHome={false} /> 
    <div className="container mx-auto px-4 pb-5" style={{ marginTop: '100px' }}>
      {/* Header and Filters */}
      <div className="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <h1 className="text-3xl font-bold text-white">Dashboard</h1>
        <div className="flex flex-wrap gap-2">
          <select className="border border-blue-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" style={{ background: '#161f30' }}>
            <option>All Projects</option>
            {/* Populate options dynamically later */}
          </select>
          <select className="border border-blue-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" style={{ background: '#161f30' }}>
            <option>Status: All</option>
            <option>Pending</option>
            <option>In Progress</option>
            <option>Completed</option>
          </select>
          <input
            type="text"
            placeholder="Search..."
            className="border border-blue-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            style={{ background: '#161f30' }}
          />
        </div>
      </div>

      {/* Stats Summary Cards */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {[
          { title: 'Total Tasks', count: 0, color: 'from-blue-800 to-blue-600' },
          { title: 'Completed', count: 0, color: 'from-green-700 to-green-500' },
          { title: 'Pending', count: 0, color: 'from-yellow-700 to-yellow-500' },
        ].map((card, i) => (
          <div key={i} className={`bg-gradient-to-br ${card.color} rounded-xl p-6 shadow flex flex-col items-center`}>
            <span className="text-lg text-white font-semibold mb-2">{card.title}</span>
            <span className="text-4xl font-bold text-white">{card.count}</span>
          </div>
        ))}
      </div>

      {/* Charts Placeholder */}
      <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        {['taskStatusLineChart', 'userBarChart', 'priorityPieChart', 'projectDonutChart'].map((id, i) => (
          <div key={i} className="rounded-xl p-6 shadow flex flex-col" style={{ background: '#161f30' }}>
            <h2 className="text-xl font-semibold text-white mb-4">Chart: {id}</h2>
            <canvas id={id} height={120}></canvas>
          </div>
        ))}
      </div>

      {/* Timeline & Activity */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div className="rounded-xl p-6 shadow flex flex-col" style={{ background: '#161f30', minHeight: 260 }}>
          <h2 className="text-xl font-semibold text-white mb-4">Tasks Timeline</h2>
          <ul className="space-y-4 text-white">
            <li className="relative pl-6">
              <span className="absolute left-0 top-1 w-3 h-3 bg-blue-500 rounded-full"></span>
              <span className="pl-5 text-blue-200 font-semibold">2025-06-01</span> — Task "Design UI" created
            </li>
            {/* More static/dynamic timeline entries */}
          </ul>
        </div>

        <div className="rounded-xl p-6 shadow flex flex-col" style={{ background: '#161f30' }}>
          <h2 className="text-xl font-semibold text-white mb-4">Recent Activity</h2>
          <ul className="divide-y divide-blue-900">
            <li className="py-3 flex items-center gap-3">
              <span className="w-2 h-2 rounded-full bg-green-400"></span>
              <span className="text-blue-100">Task <span className="font-semibold text-white">"Setup AWS S3"</span> was completed by <span className="font-semibold text-white">Alex</span></span>
              <span className="ml-auto text-xs text-blue-400">2 hours ago</span>
            </li>
            {/* More activity entries */}
          </ul>
        </div>
      </div>
    </div>
    </>
  );
};

export default Dashboard;
