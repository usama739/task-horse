import React from 'react'
import { motion } from 'framer-motion'

function CallToAction() {
  return (
    <>
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ delay: 1.4, duration: 0.7 }}  
      id="signup" 
      className="py-24 px-5 bg-gradient-to-r from-black via-blue-600 to-black text-center">
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
    </motion.div>
    </>
  )
}

export default CallToAction