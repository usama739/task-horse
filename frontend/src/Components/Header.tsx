import React from 'react'
import type { FC } from "react";
import { Link, useLocation } from "react-router-dom";
import { SignedIn, SignedOut,SignInButton, SignUpButton, UserButton } from '@clerk/clerk-react'

interface HeaderProps {
  isHome: boolean;
}

const Header: FC<HeaderProps> = ({ isHome  }) => {
   const location = useLocation();

    const getNavLinkClass = (path: string) => {
      const isActive = location.pathname.includes(path);
      return `block py-1 px-3 rounded hover:text-blue-400 cursor-pointer transition ${
        isActive ? "text-blue-600" : "text-white"
      }`;
    };

  return (
    <header
      className={`${
        isHome
          ? "w-full px-6 py-4 flex justify-between items-center bg-gradient-to-r from-black via-blue-950 to-blue-700 shadow-lg"
          : "bg-[#0e1525] py-3 fixed w-full z-20 top-0 start-0"
      }`}
    >
      <div className="container mx-auto flex flex-wrap items-center justify-between">
        <Link to={isHome ? "/" : "/tasks"} className="flex items-center gap-2">
          <img src="/logo4.png" alt="TaskHorse Logo" style={{ height: 40 }} />
        </Link>

        {isHome ? (
          <nav className="flex items-center gap-4">
            <SignedOut>
              <SignInButton />
              <SignUpButton />
            </SignedOut>
            
          </nav>
        ) : (
          <nav className="flex items-center gap-6 bg-gray-800 px-4 p-2 border:none rounded-full underlined">
            <ul className="flex space-x-2 items-center">
              <li>
                <Link to="/tasks" className={getNavLinkClass("/tasks")}>
                  Tasks
                </Link>
              </li>
              <li>
                <Link to="/team-members" className={getNavLinkClass("/team-members")}>
                  Team Members
                </Link>
              </li>
              <li>
                <Link to="/projects" className={getNavLinkClass("/projects")}>
                  Projects
                </Link>
              </li>
            </ul>
           
          </nav>
        )}

        <SignedIn>
          <UserButton />
        </SignedIn>

      </div>
    </header>
  )
}

export default Header