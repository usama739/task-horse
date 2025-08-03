import { motion } from 'framer-motion'

function HeroSection() {
  return (
    <>
    <section className="relative flex-1 flex items-center justify-center bg-gradient-to-br from-black via-blue-950 to-blue-700 py-24 px-6 clip-bottom-diagonal">
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ delay: 0.2, duration: 0.8 }}  
        className="max-w-3xl mx-auto text-center z-10">
        <h1 className="text-5xl md:text-5xl font-extrabold leading-tight mb-6 bg-gradient-to-r from-blue-400 via-cyan-400 to-green-300 bg-clip-text text-white  parallax-title drop-shadow-lg">
          Stay on Track. Get Things Done.
        </h1>
        <p className=" text-blue-100 mb-10 font-medium">
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
            <div 
              key={i} 
              className="rounded-lg px-6 py-3 flex items-center gap-3 shadow" style={{ background: '#1a2238' }}>
              <i className={`fas ${item.icon} ${item.iconColor} text-2xl`} />
              <span className={`${item.textColor} font-semibold`}>{item.label}</span>
            </div>
          ))}
        </div>
      </motion.div>
      <div className="absolute inset-0 pointer-events-none z-0">
        <div className="absolute inset-0 bg-gradient-to-br from-blue-900/60 via-blue-800/40 to-blue-700/20 blur-2xl" />
      </div>
    </section>
    </>
  )
}

export default HeroSection