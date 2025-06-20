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
      return `block py-1 px-3 rounded hover:bg-blue-500 text-white cursor-pointer transition ${
        isActive ? "bg-blue-600" : ""
      }`;
    };

  return (
    <header
      className={`${
        isHome
          ? "w-full px-6 py-4 flex justify-between items-center bg-gradient-to-r from-black via-blue-950 to-blue-700 shadow-lg"
          : "bg-transparent shadow-sm py-3 fixed w-full z-20 top-0 start-0"
      }`}
    >
      <div className="container mx-auto flex flex-wrap items-center justify-between px-3">
        <Link to={isHome ? "/" : "/tasks"} className="flex items-center gap-2">
          <img src="/logo4.png" alt="TaskHorse Logo" style={{ height: 40 }} />
        </Link>

        {isHome ? (
          <nav className="flex items-center gap-4">
            <SignedOut>
              <SignInButton />
              <SignUpButton />
            </SignedOut>
            <SignedIn>
              <UserButton />
            </SignedIn>
          </nav>
        ) : (
          <nav className="flex items-center gap-6">
            <ul className="flex space-x-2 items-center">
              <li>
                <Link to="/tasks" className={getNavLinkClass("/tasks")}>
                  Tasks
                </Link>
              </li>
              <li>
                <Link to="/users" className={getNavLinkClass("/users")}>
                  Users
                </Link>
              </li>
              <li>
                <Link to="/projects" className={getNavLinkClass("/projects")}>
                  Projects
                </Link>
              </li>
            </ul>
            <SignedIn>
              <UserButton />
            </SignedIn>
          </nav>
        )}
      </div>
    </header>
  )
}

export default Header