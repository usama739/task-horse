import './App.css'
import { BrowserRouter as Router, Routes, Route, Navigate } from "react-router-dom";
import Home from './Pages/Home'
import Projects from './Pages/Projects'
import TeamMembers from './Pages/TeamMembers';
import Tasks from './Pages/Tasks';

function App() {
  return (
     <Router>
      <Routes>
        {/* <Route path="/" element={<Navigate to="/Home" />} /> */}
        <Route path="/" element={<Home />} />
        <Route path="/projects" element={<Projects />} />
        <Route path="/team-members" element={<TeamMembers />} />
        <Route path="/tasks" element={<Tasks />} />
        {/* <Route path="/dashboard" element={<Dashboard />} /> */}
        {/* <Route path="*" element={<NotFound />} /> */}
      </Routes>
    </Router>
  )
}

export default App
