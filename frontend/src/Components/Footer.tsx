import { motion } from 'framer-motion'

function Footer() {
  return (
    <>
    <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 1.8, duration: 0.7 }}  
          className="py-8  px-5 text-center text-blue-200 text-sm border-t border-blue-900" style={{ background: '#161f30' }}>
      <div className="mb-2">
        {['Features', 'Pricing', 'Docs', 'Contact'].map((link, i) => (
          <a key={i} href="#" className="mx-2 hover:underline">{link}</a>
        ))}
      </div>
      <div className="flex flex-col items-center justify-center gap-2 mt-4">
        <span className="flex items-baseline md:items-center gap-2 text-green-300">
          <i className="fas fa-cloud" />
          Secure AWS Cloud Storage – All files encrypted in transit and at rest
        </span>
        <span className="flex items-center gap-2 text-blue-200">
          <i className="fas fa-shield-alt" />
          Enterprise‑grade security for your team’s data
        </span>
      </div>
      <p className="mt-4">&copy; {new Date().getFullYear()} TaskHorse SaaS. All rights reserved.</p>
    </motion.div>
    </>
  )
}

export default Footer