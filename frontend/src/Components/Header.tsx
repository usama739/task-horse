import { type FC, useState } from "react";
import { Link } from "react-router-dom";
import { SignedIn, SignedOut, SignInButton, SignUpButton, UserButton } from "@clerk/clerk-react";
import { useUserStore } from "../store/userStore";
import { Menu, X } from "lucide-react";

interface HeaderProps {
  isHome: boolean;
}

const Header: FC<HeaderProps> = ({ isHome }) => {
  const isAdmin = useUserStore((state) => state.isAdmin);
  const [menuOpen, setMenuOpen] = useState(false);

  const getNavLinkClass = (path: string) => {
    const isActive = location.pathname.includes(path);
    return `block py-2 px-3 rounded hover:text-blue-400 cursor-pointer transition ${
      isActive ? "text-blue-600" : "text-white"
    }`;
  };

  return (
    <header
      className={`${
        isHome
          ? "w-full px-6 py-4 flex justify-between items-center bg-gradient-to-r from-black via-blue-950 to-blue-700 shadow-lg"
          : "bg-[#0e1525] py-3 px-3 fixed w-full z-20 top-0 start-0"
      }`}
    >
      <div className="container mx-auto flex flex-wrap items-center justify-between">
        {/* Logo */}
        <Link to={isHome ? "/" : "/tasks"} className="flex items-center gap-2">
          <img src="/logo4.png" alt="TaskHorse Logo" style={{ height: 40 }} />
        </Link>


        {/* Desktop Nav + User */}
        {isHome ? (
          <nav className="hidden md:flex items-center gap-4">
            <SignedOut>
              <SignInButton />
              <SignUpButton />
            </SignedOut>
          </nav>
        ) : (
          <nav className="hidden md:flex items-center gap-6 bg-gray-800 px-4 py-2 rounded-full">
            <ul className="flex space-x-4 items-center">
              {isAdmin() && (
                <Link to="/dashboard" className={getNavLinkClass("/dashboard")}> 
                  Dashboard
                </Link>
              )}
              <li>
                <Link to="/tasks" className={getNavLinkClass("/tasks")}> 
                  Tasks
                </Link>
              </li>
              {isAdmin() && (
                <>
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
                </>
              )}
              <li>
                <SignedIn>
                  <UserButton />
                </SignedIn>
              </li>
            </ul>
          </nav>
        )}

        {/* Mobile Hamburger */}
        <button
          onClick={() => setMenuOpen(!menuOpen)}
          className="md:hidden text-white ml-3 focus:outline-none"
        >
          {menuOpen ? <X size={28} /> : <Menu size={28} />}
        </button>
      </div>

      {/* Mobile Dropdown Menu */}
      {menuOpen && (
        <div className="md:hidden bg-[#0e1525] px-6 py-4 space-y-3 absolute left-0 right-0 top-full w-full z-30 shadow-2xl border-b border-blue-900">
          {isHome ? (
            <div className="flex flex-col gap-2">
              <SignedOut>
                <SignInButton />
                <SignUpButton />
              </SignedOut>
              <SignedIn>
                <UserButton />
              </SignedIn>
            </div>
          ) : (
            <ul className="flex flex-col gap-3">
              {isAdmin() && (
                <Link to="/dashboard" className={getNavLinkClass("/dashboard")}> 
                  Dashboard
                </Link>
              )}
              <Link to="/tasks" className={getNavLinkClass("/tasks")}> 
                Tasks
              </Link>
              {isAdmin() && (
                <>
                  <Link to="/team-members" className={getNavLinkClass("/team-members")}> 
                    Team Members
                  </Link>
                  <Link to="/projects" className={getNavLinkClass("/projects")}> 
                    Projects
                  </Link>
                </>
              )}
              <SignedOut>
                <SignInButton />
                <SignUpButton />
              </SignedOut>
              <SignedIn>
                <UserButton />
              </SignedIn>
            </ul>
          )}
        </div>
      )}
    </header>
  );
};

export default Header;