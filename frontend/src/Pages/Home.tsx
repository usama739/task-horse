import React from 'react'
import Header from '../Components/Header'
import HeroSection from '../Components/HeroSection'
import Features from '../Components/Features'
import WorkFlow from '../Components/WorkFlow'
import CallToAction from '../Components/CallToAction'
import Footer from '../Components/Footer'

function Home() {
  return (
    <div className='text-white font-sans min-h-screen flex flex-col' >
        <Header />
        <HeroSection />
        <Features />
        <WorkFlow />
        <CallToAction />
        <Footer />
    </div>
  )
}

export default Home