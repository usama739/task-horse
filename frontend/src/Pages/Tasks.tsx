import React, { useEffect, useState, type FormEvent, type ChangeEvent } from "react";
import axios from "axios";
import Header from "../Components/Header";
import FullCalendar from '@fullcalendar/react';
import dayGridPlugin from '@fullcalendar/daygrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

interface Task {
  id: number;
  title: string;
  description: string;
  priority: "Low" | "Medium" | "High";
  status: "Pending" | "In-Progress" | "Completed";
  category?: { name: string };
  due_date?: string;  
  assignedTo?: string;
}

const TasksPage: React.FC = () => {
  const [tasks, setTasks] = useState<Task[]>([]);
  const [timelineTasks, setTimelineTasks] = useState<Task[]>([]);
  const [counts, setCounts] = useState({ pending: 0, inProgress: 0, completed: 0 });
  const [search, setSearch] = useState("");
  const [filterPriority, setFilterPriority] = useState("");
  const [filterStatus, setFilterStatus] = useState("");

  const [deleteModalOpen, setDeleteModalOpen] = useState(false);
  const [deleteTarget, setDeleteTarget] = useState<Partial<Task>>({});
    
  const [showModal, setShowModal] = useState(false);
  const [taskId, setTaskId] = useState<number | null>(null);
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    priority: '',
    status: '',
    due_date: '',
    category_id: '',
    user_id: '',
  });

  const [categories, setCategories] = useState([]);
  const [users, setUsers] = useState([]);
  const [attachments, setAttachments] = useState<FileList | null>(null);

  useEffect(() => {
    axios.get<any>('/api/categories').then(res => setCategories(res.data));
    axios.get<any>('/api/users').then(res => setUsers(res.data));

    axios.get<any>('/api/timeline-tasks') 
      .then(res => {
        setTimelineTasks(res.data);
      })
      .catch(err => {
        console.error('Failed to load timeline tasks', err);
      });

    fetchTasks();
  }, []);

//   useEffect(() => {
//     const filtered = tasks.filter((t) => {
//       const matchSearch = t.title.toLowerCase().includes(search.toLowerCase());
//       const matchPriority = filterPriority ? t.priority === filterPriority : true;
//       const matchStatus = filterStatus ? t.status === filterStatus : true;
//       return matchSearch && matchPriority && matchStatus;
//     });

//     setCounts({
//       pending: filtered.filter((t) => t.status === "Pending").length,
//       inProgress: filtered.filter((t) => t.status === "In-Progress").length,
//       completed: filtered.filter((t) => t.status === "Completed").length,
//     });
//   }, [tasks, search, filterPriority, filterStatus]);

  const fetchTasks = async () => {
    const res = await axios.get<Task[]>("/api/tasks");
    setTasks(res.data);
  };

// Load task data when editing
  useEffect(() => {
    if (taskId !== null) {
      axios.get<any>(`/api/tasks/${taskId}`).then(res => {
        setFormData(res.data);
        setShowModal(true);
      });
    }
  }, [taskId]);

  const openModal = () => {
    setTaskId(null);
    setFormData({
      title: '',
      description: '',
      priority: '',
      status: '',
      due_date: '',
      category_id: '',
      user_id: '',
    });
    setShowModal(true);
  };

  const handleClose = () => {
    setShowModal(false);
    setTaskId(null);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (taskId) {
      await axios.put(`/api/tasks/${taskId}`, formData);
    } else {
      await axios.post('/api/tasks', formData);
    }
    handleClose();
    window.location.reload(); // or refetch tasks state
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleDeleteModal = (task: Task) => {
    setDeleteTarget(task);
    setDeleteModalOpen(true);
  };

  const handleDelete = async () => {
    await axios.delete(`/api/tasks/${deleteTarget.id}`);
    setDeleteModalOpen(false);
    fetchTasks();
  };

  
  return (
    <>
    <Header isHome={false} />

    <div className="container mx-auto px-4 py-8 text-white" style={{ paddingTop : '135px' }}>
      {/* Cards */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div className="card border-l-4 border-yellow-600 bg-[#161f30] shadow-md rounded-lg p-6 flex flex-col items-center">
          <h5 className="flex items-center gap-2 text-yellow-300 font-medium mb-2">
            <i className="fas fa-hourglass-half text-yellow-400 fa-2x" /> Pending
          </h5>
          <h3 className="text-3xl font-bold">{counts.pending}</h3>
        </div>
        <div className="card border-l-4 border-blue-600 shadow-md bg-[#161f30] rounded-lg p-6 flex flex-col items-center">
          <h5 className="flex items-center gap-2 text-blue-300 font-medium">
            <i className="fas fa-spinner text-blue-400 fa-2x" /> In-Progress
          </h5>
          <h3 className="text-3xl font-bold">{counts.inProgress}</h3>
        </div>
        <div className="card border-l-4 border-green-600 bg-[#161f30] shadow-md rounded-lg p-6 flex flex-col items-center">
          <h5 className="flex items-center gap-2 text-green-300 font-medium">
            <i className="fas fa-check-circle text-green-400 fa-2x" /> Completed
          </h5>
          <h3 className="text-3xl font-bold">{counts.completed}</h3>
        </div>
      </div>


      <div className="shadow rounded-lg p-6 mt-24">
        <div className="flex flex-col md:flex-row md:justify-between md:items-center mb-6 border-b border-blue-500 pb-4">
          <h4 className="text-3xl font-semibold mb-4 md:mb-0 text-white">Tasks</h4>

          <div className="flex flex-col md:flex-row gap-2 w-full md:w-auto">
              <div className="relative">
                  <div className="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                      <svg className="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" strokeWidth="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"></path>
                      </svg>
                  </div>
                  <input 
                      type="search" 
                      id="search" 
                      className="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                      placeholder="Search Tasks..."
                      value={search}
                      onChange={(e: ChangeEvent<HTMLInputElement>) => setSearch(e.target.value)}
                  />
              </div>
          
            <select
              value={filterPriority}
              onChange={e => setFilterPriority(e.target.value)}
              className="p-2 text-sm rounded-lg bg-gray-700 text-white px-3 cursor-pointer"
            >
              <option value="">All Priorities</option>
              <option value="Low">Low</option>
              <option value="Medium">Medium</option>
              <option value="High">High</option>
            </select>

            <select
              value={filterStatus}
              onChange={e => setFilterStatus(e.target.value)}
              className="p-2 text-sm rounded-lg bg-gray-700 text-white px-3 cursor-pointer"
            >
              <option value="">All Statuses</option>
              <option value="Pending">Pending</option>
              <option value="In-Progress">In-Progress</option>
              <option value="Completed">Completed</option>
            </select>

            {/* {currentUserRole !== 'user' && ( */}
              <button className="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold cursor-pointer" onClick={openModal}>
                Add Task
              </button>
            {/* )} */}
          </div>
        </div>

        <div className="overflow-x-auto rounded-lg shadow">
          <table className="w-full text-sm text-center text-gray-300">
            <thead className="text-xs uppercase bg-gray-900 text-gray-400">
              <tr>
                <th className="px-6 py-3">Title</th>
                <th className="px-6 py-3">Priority</th>
                <th className="px-6 py-3">Status</th>
                <th className="px-6 py-3">Category</th>
                <th className="px-6 py-3">Due Date</th>
                {/* {currentUserRole !== 'user' && */}
                <th className="px-6 py-3">Assigned To</th>
                {/* } */}
                <th className="px-6 py-3">Actions</th>
              </tr>
            </thead>
            <tbody style={{ background: '#0c1220' }}>
              {/* {tasks.map(task => (
                <tr key={task.id} className="border-b">
                  <td className="px-6 py-4">{task.title}</td>
                  <td>
                    <span className={`inline-block rounded px-2 py-1 text-xs font-semibold text-white bg-${getPriorityColor(task.priority)}-500 bg-opacity-20`}>
                      {task.priority}
                    </span>
                  </td>
                  <td>
                    <span className={`inline-block rounded px-2 py-1 text-xs font-semibold text-white bg-${getStatusColor(task.status)}-500 bg-opacity-20`}>
                      {task.status}
                    </span>
                  </td>
                  <td>{task.category?.name || 'No Category'}</td>
                  <td>{task.due_date ? formatDate(task.due_date) : 'No Due Date'}</td>
                  {currentUserRole !== 'user' &&  
                      <td>{task.user?.name}</td>
                  }
                  <td>
                    <div className="inline-flex gap-1">
                      <button className="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">View</button>
                      {currentUserRole !== 'user' && (
                        <>
                          <button className="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded" onClick={() => handleEditClick(task.id)}>Edit</button>
                          <button className="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded" onClick={() => handleDeleteModal(task)}>Delete</button>
                        </>
                      )}
                    </div>
                  </td>
                </tr>
              ))} */}
              {/* {filteredTasks.length === 0 && ( */}
                <tr>
                  <td colSpan={7} className="text-center py-6 text-gray-400">No task found.</td>
                </tr>
              {/* )} */}
            </tbody>
          </table>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-8 mt-16">
        {/* Calendar Section */}
        {/* <div className="mb-4">
          <FullCalendar
            plugins={[dayGridPlugin, listPlugin, interactionPlugin]}
            initialView="dayGridMonth"
            headerToolbar={{
              left: 'prev,next today',
              center: 'title',
              right: 'dayGridMonth,listWeek'
            }}
            events={(fetchInfo, successCallback, failureCallback) => {
              axios.get('/api/tasks/events')
                .then(response => successCallback(response.data))
                .catch(err => failureCallback(err));
            }}
            eventClick={(info) => {
              alert(`Task: ${info.event.title}\nDue Date: ${info.event.start?.toISOString().split('T')[0]}`);
            }}
          />
        </div> */}

        {/* Timeline Section */}
        {/* <div>
          <h2 className="text-center text-2xl font-bold mb-4">Task Timeline</h2>
          <div className="main-timeline">
            {timelineTasks.map((task, index) => (
              <div key={task.id} className={`timeline flex ${index % 2 === 0 ? 'left' : 'right'} mb-6`}>
                <div className="card">
                  <div className="card-body p-6 w-full max-w-md">
                    <h3 className="text-lg font-semibold text-gray-400 mb-2">
                      {new Date(task.due_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                    </h3>
                    <div className="text-xl font-bold text-gray-100 mb-1">{task.title}</div>
                    <div className="text-gray-400 text-sm mb-2">{task.user.name}</div>
                    <p className={`font-semibold ${getPriorityColor(task.priority)}`}>{task.priority}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div> */}
      </div>


      {showModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm" style={{ marginTop: '300px' }}>
          <div className="bg-[#161f30] text-white rounded-xl w-full max-w-xl shadow-xl">
            <div className="flex justify-between items-center mb-4 border-b border-blue-700 px-6 py-4">
              <h2 className="text-2xl font-semibold">{taskId ? 'Edit Task' : 'Add Task'}</h2>
              <button onClick={handleClose} className="text-2xl font-bold text-blue-400 hover:text-red-500 cursor-pointer">&times;</button>
            </div>

            <form onSubmit={handleSubmit} className="space-y-4 p-6">
              <input type="hidden" name="taskId" value={taskId ?? ''} />

              <div>
                <label className="block text-gray-400 mb-1">Title</label>
                <input name="title" required value={formData.title} onChange={handleChange} className="w-full bg-[#1a2238] border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2" />
              </div>
              
              <div>
                <label className="block text-gray-400 mb-1">Description</label>
                <textarea name="description" value={formData.description} onChange={handleChange} className="w-full bg-[#1a2238] border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2" />
              </div>

              <div>
                <label className="block text-gray-400 mb-1">Priority</label>
                <select name="priority" value={formData.priority} onChange={handleChange} className="w-full bg-[#1a2238] p-2 rounded-lg border border-gray-600 mb-2">
                  <option value="">Select Priority</option>
                  <option value="Low">Low</option>
                  <option value="Medium">Medium</option>
                  <option value="High">High</option>
                </select>
              </div>

              <div>
                <label className="block text-gray-400 mb-1">Status</label>
                <select name="status" value={formData.status} onChange={handleChange} className="w-full bg-[#1a2238] p-2 rounded-lg border border-gray-600 mb-2">
                  <option value="">Select Status</option>
                  <option value="Pending">Pending</option>
                  <option value="In-Progress">In Progress</option>
                  <option value="Completed">Completed</option>
                </select>
              </div>

              <div>
                <label className="block text-gray-400 mb-1">Due Date</label>
                  <input type="date" name="due_date" value={formData.due_date} onChange={handleChange} className="w-full bg-[#1a2238] p-2 rounded-lg border border-gray-600 mb-2" />
              </div>

              <div>
                <label className="block text-gray-400 mb-1">Category</label>
                <select name="category_id" value={formData.category_id} onChange={handleChange} className="w-full bg-[#1a2238] p-2 rounded-lg border border-gray-600 mb-2">
                  <option value="">Select Category</option>
                  {/* {categories.map((cat: any) => (
                    <option key={cat.id} value={cat.id}>{cat.name}</option>
                  ))} */}
                </select>
              </div>

              <div>
                <label className="block text-gray-400 mb-1">Assign To</label>
                <select name="user_id" value={formData.user_id} onChange={handleChange} className="w-full bg-[#1a2238] p-2 rounded-lg border border-gray-600 mb-2">
                  <option value="">Select User</option>
                  {/* {users.map((user: any) => (
                    <option key={user.id} value={user.id}>{user.name}</option>
                  ))} */}
                </select>
              </div>
              
                <div>
                <label className="block font-medium text-gray-400 mb-1">Attachments</label>
                <input type="file" name="attachments" multiple onChange={e => setAttachments(e.target.files)} className="w-full rounded-lg px-3 py-2 border border-gray-600 focus:ring-2 focus:ring-blue-500" style={{ background: '#1a2238' }} />
              </div>

              <div className="flex justify-center gap-2 pt-4">
                <button type="button" onClick={handleClose} className="bg-gray-400 px-4 py-2 rounded-lg text-black font-semibold">Cancel</button>
                <button type="submit" className="bg-blue-600 hover:bg-blue-800 px-4 py-2 rounded-lg text-white font-semibold">
                  Save Task
                </button>
              </div>
            </form>
          </div>
        </div>
      )}


      {/* Delete Confirmation Modal */}
      {deleteModalOpen && (
        <div
          className="fixed inset-0 z-50 backdrop-blur-sm flex justify-center items-center"
          onClick={(e) => e.target === e.currentTarget && setDeleteModalOpen(false)}
        >
          <div className="text-white bg-[#161f30] rounded-xl shadow-2xl w-full max-w-md p-6 animate-fade-in">
            <h3 className="text-xl font-semibold mb-4">Confirm Deletion</h3>
            <p className="mb-6">
              Are you sure you want to delete{' '}
              <strong>{deleteTarget.title}</strong>?
            </p>
            <div className="flex justify-center gap-3">
              <button
                className="bg-gray-200 text-gray-700 font-semibold rounded-lg px-4 py-2"
                onClick={() => setDeleteModalOpen(false)}
              >
                Cancel
              </button>
              <button
                className="bg-red-600 hover:bg-red-800 text-white font-semibold rounded-lg px-4 py-2"
                onClick={handleDelete}
              >
                Yes, Delete
              </button>
            </div>
          </div>
        </div>
      )}

    </div>

    </>
  );
};


// Helper functions
const getPriorityColor = (priority: string) => {
  switch (priority) {
    case 'High': return 'red';
    case 'Medium': return 'yellow';
    case 'Low': return 'green';
    default: return 'gray';
  }
};

const getStatusColor = (status: string) => {
  switch (status) {
    case 'Completed': return 'green';
    case 'In-Progress': return 'indigo';
    case 'Pending': return 'yellow';
    default: return 'gray';
  }
};

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-GB', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  });
};

const handleEditClick = (id: number) => {
  const modal = document.getElementById('task-modal') as any;
  if (modal && modal.setTaskId) {
    modal.setTaskId(id); // trigger edit state in modal
  }
};


export default TasksPage;
