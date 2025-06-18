import './App.css'
import { BrowserRouter as Router, Routes, Route, Navigate } from "react-router-dom";
import Home from './Pages/Home'

function App() {
  return (
     <Router>
      <Routes>
        {/* <Route path="/" element={<Navigate to="/Home" />} /> */}
        <Route path="/" element={<Home />} />
        {/* <Route path="/dashboard" element={<Dashboard />} /> */}
        {/* <Route path="/projects" element={<ProjectsPage />} /> */}
        {/* <Route path="*" element={<NotFound />} /> */}
      </Routes>
    </Router>
  )
}

export default App
