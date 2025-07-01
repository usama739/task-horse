import React from 'react'
import { motion } from 'framer-motion'

function WorkFlow() {
  return (
    <>
    <section className="py-24" style={{ background: '#0e1525' }}>
      <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 1, duration: 0.8 }}  
          className="container mx-auto px-6">
        <h2 className="text-3xl md:text-4xl font-bold text-center text-white">How It Works</h2>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-12 mt-10">
          {[
            { icon: 'fa-user-plus', title: '1. Sign Up & Invite', desc: 'Create your account and invite your team. All user data is protected with enterprise‑grade security.' },
            { icon: 'fa-layer-group', title: '2. Organize & Assign', desc: 'Create projects, assign tasks, and upload files. Every file is encrypted and stored in the AWS cloud for maximum security.' },
            { icon: 'fa-rocket', title: '3. Track & Succeed', desc: 'Monitor progress, collaborate, and share files with confidence—your data is always safe with cloud‑based encryption.' },
          ].map((step, i) => (
            <div key={i} className="flex flex-col items-center zoom-in">
              <div className="bg-blue-700 rounded-full p-5 mb-4 shadow-lg">
                <i className={`fas ${step.icon} text-3xl text-white`} />
              </div>
              <h4 className="text-lg font-bold mb-2">{step.title}</h4>
              <p className="text-blue-100 text-center">{step.desc}</p>
            </div>
          ))}
        </div>
      </motion.div>
    </section>
    </>
  )
}

export default WorkFlow