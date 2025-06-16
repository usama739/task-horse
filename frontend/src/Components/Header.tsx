import React from 'react'
import { SignedIn, SignedOut,SignInButton, SignUpButton, UserButton } from '@clerk/clerk-react'

function Header() {
  return (
    <>
     <header className="w-full px-6 py-4 flex justify-between items-center bg-gradient-to-r from-black via-blue-950 to-blue-700 shadow-lg">
      <img src="logo4.png" alt="TaskHorse Logo" className="h-10" />
      <nav className="flex items-center gap-4">
        
        <SignedOut>
          <SignInButton />
          <SignUpButton />       
        </SignedOut>

        <SignedIn>
          <UserButton />
        </SignedIn>

        {/* <a href="/login" className="px-4 py-2 rounded-md font-semibold bg-white text-blue-700 hover:bg-gray-100 transition shadow">
          Log in
        </a>
        <a href="/register" className="px-4 py-2 rounded-md font-semibold bg-blue-500 hover:bg-blue-600 transition text-white shadow">
          Register
        </a> */}
      </nav>
    </header>   
    </>
  )
}

export default Header