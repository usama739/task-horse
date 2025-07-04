import './App.css'
import { BrowserRouter as Router, Routes, Route, Navigate } from "react-router-dom";
import Home from './Pages/Home'
import Projects from './Pages/Projects'
import TeamMembers from './Pages/TeamMembers';
import Tasks from './Pages/Tasks';
import NotFound from './Pages/NotFound';
import ViewTask from './Pages/ViewTask';
import CreateOrganization from './Pages/Organization';
import { SignedIn } from '@clerk/clerk-react'
import Dashboard from './Pages/Dashboard';

function App() {
  return (
     <Router>
      <Routes>
        {/* <Route path="/" element={<Navigate to="/Home" />} /> */}
        <Route path="/" element={<Home />} />
        <Route
          path="/create-organization"
          element={
            <SignedIn>
              <CreateOrganization />
            </SignedIn>
          }
        />
        <Route path="/projects" element={<Projects />} />
        <Route path="/team-members" element={<TeamMembers />} />
        <Route path="/tasks" element={<Tasks />} />
        <Route path="/tasks" element={<Tasks />} />
        <Route path="/task/:id" element={<ViewTask />} />
        <Route path="/dashboard" element={<Dashboard />} />
        <Route path="*" element={<NotFound />} />
      </Routes>
    </Router>
  )
}

export default App
