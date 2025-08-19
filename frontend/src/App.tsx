import './App.css'
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Home from './Pages/Home'
import Projects from './Pages/Projects'
import TeamMembers from './Pages/TeamMembers';
import Tasks from './Pages/Tasks';
import NotFound from './Pages/NotFound';
import ViewTask from './Pages/ViewTask';
import CreateOrganization from './Pages/Organization';
import { SignedIn, SignedOut, RedirectToSignIn } from '@clerk/clerk-react'
import Dashboard from './Pages/Dashboard';
import AuthRedirect from './Pages/AuthRedirect';
import { FetchLaravelUser } from './Hooks/fetchLaravelUser';
import { useUserStore } from './store/userStore';

function App() {
  FetchLaravelUser();           /// save athenticated user in zustand store when app loads (after signUp/signIn)

  const user = useUserStore((state) => state.user);
  console.log("User Stored in Zustand: ", user);
  
  return (
     <Router>
      <Routes>
        <Route path="/" element={<Home />} />
      
          <Route path="/create-organization"
            element={
              <SignedIn>
                <CreateOrganization />
              </SignedIn>
            }
          />
          <Route path="/projects" element={<SignedIn>
            <Projects />
          </SignedIn>} />
          <Route path="/team-members" element={<SignedIn>
            <TeamMembers />
          </SignedIn>} />
          <Route path="/tasks" element={<SignedIn>
            <Tasks />
          </SignedIn>} />
          <Route path="/task/:id" element={<SignedIn>
            <ViewTask />
          </SignedIn>} />
          <Route path="/dashboard" element={<SignedIn>
            <Dashboard />
          </SignedIn>} />

          <Route path="*" element={<NotFound />} />
        </Routes>

      {/* Clerk redirect handler */}
      <AuthRedirect />
            
      {/* <SignedOut>
        <RedirectToSignIn />
      </SignedOut>
       */}
    </Router>
  )
}

export default App
