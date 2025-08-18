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
      </Routes>
      <SignedIn>
        <AuthRedirect />
        <Routes>
          <Route path="/create-organization"
            element={
              <SignedIn>
                <CreateOrganization />
              </SignedIn>
            }
          />
          <Route path="/projects" element={<Projects />} />
          <Route path="/team-members" element={<TeamMembers />} />
          <Route path="/tasks" element={<Tasks />} />
          <Route path="/task/:id" element={<ViewTask />} />
          <Route path="/dashboard" element={<Dashboard />} />
          <Route path="*" element={<NotFound />} />
        </Routes>
      </SignedIn>

            
      <SignedOut>
        <RedirectToSignIn />
      </SignedOut>
      
    </Router>
  )
}

export default App
