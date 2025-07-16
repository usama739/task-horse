import React, {useEffect, useState} from 'react';
import { CalendarIcon } from "lucide-react"
import { Button } from "@/Components/ui/button"
import { Calendar } from "@/Components/ui/calendar"
import dayjs from 'dayjs';
import { Popover, PopoverContent, PopoverTrigger, } from "@/Components/ui/popover"
import 'react-circular-progressbar/dist/styles.css';
import PriorityProgress from '../Components/PriorityProgress';

import Header from '../Components/Header';
import LineChart from '../Components/LineChart';
import TasksByUserChart from '../Components/TasksByUserChart';
import PriorityPieChart from '../Components/PriorityPieChart';
import ProjectDonutChart from '../Components/ProjectDonutChart';
import axios from '../axios'; 
import CountUp from 'react-countup';
import { motion } from "framer-motion";
import { useAuth } from '@clerk/clerk-react';

interface Project {
  id: number;
  name: string;
}

const Dashboard: React.FC = () => {
  const { getToken } = useAuth();
  const [projectId, setProjectId] = useState('');
  const [status, setStatus] = useState('');
  const [startDate, setStartDate] = React.useState<Date | undefined>(undefined)
  const [endDate, setEndDate] = React.useState<Date | undefined>(undefined)

  const [priorityCounts, setPriorityCounts] = useState({ high: 0, medium: 0, low: 0 });
  const [overview, setOverview] = useState({ users: 0, tasks: 0, projects: 0 });
  const [projects, setprojects] = useState<Project[]>([]);
  const [open, setOpen] = React.useState(false)
  const [openEnd, setOpenEnd] = React.useState(false)


  useEffect(() => {
    const today = new Date();
    const oneMonthAgo = new Date();
    oneMonthAgo.setDate(today.getDate() - 30);

    setStartDate(oneMonthAgo);
    setEndDate(today);

    const fetchProjects = async () => {
      const token = await getToken();
      if (!token) return;
      try {
        const response = await axios.get<Project[]>('/projects', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        setprojects(response.data);
      } catch (error) {
        console.error('Error fetching projects:', error);
      }
    };

    const fetchOverview = async () => {
      const token = await getToken();
      if (!token) return;
      try {
        const res: any = await axios.get('/dashboard/overview-counts', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        setOverview(res.data);
      } catch (error) {
        console.error('Error fetching overview:', error);
      }
    };

    fetchProjects();
    fetchOverview();
    
  }, []);



  useEffect(() => {
    const fetchPriorityCounts = async () => {
      const token = await getToken();
      if (!token || !startDate || !endDate) return;

      try {
        const response = await axios.get<any>('/dashboard/task-priority-counts', {
          params: {
            start_date: dayjs(startDate).format("YYYY-MM-DD"),
            end_date: dayjs(endDate).format("YYYY-MM-DD"),
            project_id: projectId,
            status: status,
          },
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        setPriorityCounts(response.data);
      } catch (error) {
        console.error('Error fetching priority counts:', error);
      }
    };

    fetchPriorityCounts();
  }, [startDate, endDate, projectId, status]);



  return (
    <>
      <Header isHome={false} /> 
      
      <div className="container mx-auto px-4 pb-5" style={{ marginTop: '120px' }}>
        {/* Header and Filters */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0  }} 
          className="mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-10">
          <h1 className="text-3xl font-bold text-white">Dashboard</h1>
          <div className="flex flex-col sm:flex-row flex-wrap gap-2 sm:items-center">
            
            {/* Start Date */}
            <div>
              <Popover open={open} onOpenChange={setOpen}>
                <PopoverTrigger asChild>
                  <Button
                    variant="outline"
                    id="date"
                    className="w-full md:w-48 justify-between font-normal text-white bg-[#161f30] border border-blue-700 hover:border-blue-600 hover:bg-[#161f30] hover:text-white focus:ring-2 focus:ring-blue-500"
                  >
                    {startDate ? dayjs(startDate).format("YYYY-MM-DD") : "Start date"}
                    <CalendarIcon />
                  </Button>
                </PopoverTrigger>
                <PopoverContent className="w-auto overflow-hidden p-0 bg-[#161f30] border border-blue-900 text-white" align="start">
                  <Calendar
                    mode="single"
                    selected={startDate}
                    captionLayout="dropdown"
                    onSelect={(date) => {
                      setStartDate(date)
                      setOpen(false)
                    }}
                  />
                </PopoverContent>
              </Popover>
            </div>

            <span className='text-white hidden md:inline'> - </span>

            {/* End Date */}
            <div>
              <Popover open={openEnd} onOpenChange={setOpenEnd}>
                <PopoverTrigger asChild>
                  <Button
                    variant="outline"
                    id="date"
                    className="w-full md:w-48 justify-between font-normal text-white bg-[#161f30] border border-blue-700 hover:border-blue-600 hover:bg-[#161f30] hover:text-white focus:ring-2 focus:ring-blue-500"
                  >
                    {endDate ? dayjs(endDate).format("YYYY-MM-DD") : "End date"}
                    <CalendarIcon />
                  </Button>
                </PopoverTrigger>
                <PopoverContent className="w-auto overflow-hidden p-0 bg-[#161f30] border border-blue-900 text-white" align="start">
                  <Calendar
                    mode="single"
                    selected={endDate}
                    captionLayout="dropdown"
                    onSelect={(date) => {
                      setEndDate(date)
                      setOpenEnd(false)
                    }}
                  />
                </PopoverContent>
              </Popover>
            </div>

            <select className="border border-blue-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer" onChange={(e) => setProjectId(e.target.value)} style={{ background: '#161f30' }}>
              <option value="">All Projects</option>
              {projects.map((proj) => (
                <option key={proj.id} value={proj.id}>{proj.name}</option>
              ))}
            </select>
            <select className="border border-blue-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer" onChange={(e) => setStatus(e.target.value)} style={{ background: '#161f30' }}>
              <option value="">Status: All</option>
              <option value="Pending">Pending</option>
              <option value="In-Progress">In Progress</option>
              <option value="Completed">Completed</option>
            </select>
          </div>
        </motion.div>


        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
          {/* Total Users */}
          <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.2 }}
          className="bg-[#1f2937] p-6 rounded-xl shadow-lg flex items-center gap-4 border border-blue-800 hover:scale-105">
            <div className="bg-blue-600 p-3 rounded-full text-white">
              <svg xmlns="http://www.w3.org/2000/svg" className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8 4 4 0 000 8z" />
              </svg>
            </div>
            <div>
              <h3 className="text-sm text-gray-400">Total Users</h3>
              <p className="text-2xl font-bold text-white">
                <CountUp end={overview.users} duration={1.5} /></p>
            </div>
          </motion.div>

          {/* Total Tasks */}
          <motion.div 
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.2 }}
          className="bg-[#1f2937] p-6 rounded-xl shadow-lg flex items-center gap-4 border border-blue-800 hover:scale-105">
            <div className="bg-yellow-500 p-3 rounded-full text-white">
              <svg xmlns="http://www.w3.org/2000/svg" className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 17v-6h13M9 17l-4-4m0 0l4-4m-4 4h13" />
              </svg>
            </div>
            <div>
              <h3 className="text-sm text-gray-400">Total Tasks</h3>
              <p className="text-2xl font-bold text-white">
                <CountUp end={overview.tasks} duration={1.5} /></p>
            </div>
          </motion.div>

          {/* Total Projects */}
          <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.2 }}
          className="bg-[#1f2937] p-6 rounded-xl shadow-lg flex items-center gap-4 border border-blue-800 hover:scale-105">
            <div className="bg-green-600 p-3 rounded-full text-white">
              <svg xmlns="http://www.w3.org/2000/svg" className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c.132 0 .263.007.393.02M4 6h16M4 10h16M4 14h16M4 18h16" />
              </svg>
            </div>
            <div>
              <h3 className="text-sm text-gray-400">Total Projects</h3>
              <p className="text-2xl font-bold text-white">
                <CountUp end={overview.projects} duration={1.5} /></p>
            </div>
          </motion.div>
        </div>

        <PriorityProgress counts={priorityCounts} />


        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.6 }}
            className="rounded-xl p-6 shadow flex flex-col" style={{ background: '#161f30', height: '450px' }}>
              <h2 className="border-b border-blue-500 text-xl font-semibold text-white mb-4 pb-4">Task Volume by Status</h2>
              <LineChart startDate={startDate} endDate={endDate} projectId={projectId} status={status}  />
            </motion.div>

            <motion.div
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.8 }} 
              className="rounded-xl p-6 shadow flex flex-col" style={{ background: '#161f30', height: '450px' }}>
                <h2 className="border-b border-blue-500 text-xl font-semibold text-white mb-4 pb-4">Tasks Completed Per User</h2>
                <TasksByUserChart startDate={startDate} endDate={endDate} projectId={projectId} status={status} />
            </motion.div>

            <motion.div
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 1 }} 
              className="rounded-xl p-6 shadow flex flex-col" style={{ background: '#161f30', height: '450px' }}>
                <h2 className="border-b border-blue-500 text-xl font-semibold text-white mb-4 pb-4">Current Tasks by Priority</h2>
                <div className="flex justify-center items-center h-full">
                  <PriorityPieChart startDate={startDate} endDate={endDate} projectId={projectId} status={status} />
                </div>
            </motion.div>

            <motion.div
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 1.2 }} 
              className="rounded-xl p-6 shadow flex flex-col" style={{ background: '#161f30', height: '450px' }}>
                <h2 className="border-b border-blue-500 text-xl font-semibold text-white mb-4 pb-4">Tasks by Project</h2>
                <div className="flex justify-center items-center h-full">
                  <ProjectDonutChart startDate={startDate} endDate={endDate} projectId={projectId} status={status} />
                </div>
            </motion.div>
        </div>


        {/* Timeline & Activity */}
        {/* <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div className="rounded-xl p-6 shadow flex flex-col" style={{ background: '#161f30', minHeight: 260 }}>
            <h2 className="border-b border-blue-500 text-xl font-semibold text-white mb-4 pb-4">Tasks Timeline</h2>
            <ul className="space-y-4 text-white">
              <li className="relative pl-6">
                <span className="absolute left-0 top-1 w-3 h-3 bg-blue-500 rounded-full"></span>
                <span className="pl-5 text-blue-200 font-semibold">2025-06-01</span> — Task "Design UI" created
              </li>
            </ul>
          </div>

          <div className="rounded-xl p-6 shadow flex flex-col" style={{ background: '#161f30' }}>
            <h2 className="border-b border-blue-500 text-xl font-semibold text-white mb-4 pb-4">Recent Activity</h2>
            <ul className="divide-y divide-blue-900">
              <li className="py-3 flex items-center gap-3">
                <span className="w-2 h-2 rounded-full bg-green-400"></span>
                <span className="text-blue-100">Task <span className="font-semibold text-white">"Setup AWS S3"</span> was completed by <span className="font-semibold text-white">Alex</span></span>
                <span className="ml-auto text-xs text-blue-400">2 hours ago</span>
              </li>
            </ul>
          </div>
        </div> */}
      </div>
    </>
  );
};

export default Dashboard;
