import React, { useState, useEffect } from 'react';
import axios from '../axios'; // your axios setup
import { useParams, Link } from 'react-router-dom';

interface User {
  id: number;
  name: string;
  role: string;
}

interface Comment {
  id: number;
  comment: string;
  created_at: string;
  user: User;
}

interface Task {
  id: number;
  title: string;
  description: string;
  priority: string;
  status: string;
  due_date: string | null;
  user: User | null;
  comments: Comment[];
}

const TaskDetail: React.FC = () => {
  const { id } = useParams();
  const [task, setTask] = useState<Task | null>(null);
  const [comment, setComment] = useState('');
  const [dropdownOpenId, setDropdownOpenId] = useState<number | null>(null);

  useEffect(() => {
    const fetchTask = async () => {
      const res: any = await axios.get(`/tasks/${id}`);
      setTask(res.data);
    };

    fetchTask();
  }, [id]);

  const getPriorityColor = (priority: string) => {
    const p = priority.toLowerCase();
    return p === 'high' ? 'red' : p === 'medium' ? 'yellow' : 'green';
  };

  const getStatusColor = (status: string) => {
    const s = status.toLowerCase();
    return s === 'completed' ? 'green' : s === 'in-progress' ? 'indigo' : 'yellow';
  };

  const handleCommentSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    await axios.post(`/tasks/${task?.id}/comments`, { comment });
    setComment('');
    const res: any = await axios.get<Task[]>(`/tasks/${id}`);
    setTask(res.data);
  };

  const handleCommentDelete = async (commentId: number) => {
    await axios.delete(`/comments/${commentId}`);
    setTask((prev) =>
      prev
        ? { ...prev, comments: prev.comments.filter((c) => c.id !== commentId) }
        : null
    );
  };

  if (!task) return <p className="text-white text-center">Loading...</p>;

  return (
    <div className="container mx-auto max-w-4xl text-white">
      <div className="flex items-center mb-6">
        <Link to="/tasks" className="flex items-center text-blue-400 hover:text-blue-600 text-lg font-semibold">
          <i className="fas fa-arrow-left mr-2"></i> <span>Back to Tasks</span>
        </Link>
      </div>

      <div className="rounded-lg shadow-md p-8 mb-8" style={{ background: '#161f30' }}>
        <h2 className="text-3xl font-bold mb-5">{task.title}</h2>
        <div className="mb-5">
          <span className="block text-gray-400 font-medium mb-1">Description:</span>
          <p className="text-gray-200">{task.description}</p>
        </div>

        <div className="flex flex-wrap gap-8 mb-3">
          <div>
            <span className="block text-gray-400 font-medium mb-1">Priority:</span>
            <span className={`inline-block rounded px-2 py-1 text-xs font-semibold bg-opacity-20 text-white bg-${getPriorityColor(task.priority)}-500`}>
              {task.priority}
            </span>
          </div>

          <div>
            <span className="block text-gray-400 font-medium mb-1">Status:</span>
            <span className={`inline-block rounded px-2 py-1 text-xs font-semibold bg-opacity-20 text-white bg-${getStatusColor(task.status)}-500`}>
              {task.status}
            </span>
          </div>

          <div>
            <span className="block text-gray-400 font-medium mb-1">Due Date:</span>
            <span className="text-gray-200">{task.due_date ?? 'No due date'}</span>
          </div>

          <div>
            <span className="block text-gray-400 font-medium mb-1">Assigned To:</span>
            <span className="text-gray-200">{task.user?.name ?? 'Unassigned'}</span>
          </div>
        </div>
      </div>

      <div className="rounded-lg shadow-md p-6 mb-8" style={{ background: '#161f30' }}>
        <h3 className="text-2xl font-bold mb-4">Comments</h3>
        {task.comments.map((comment) => (
          <div key={comment.id} className="flex justify-between items-center border-b border-gray-700 py-4 px-2 relative">
            <div>
              <span className="font-semibold text-blue-300">{comment.user.name}</span>
              <span className="text-xs text-gray-400 ml-2">{new Date(comment.created_at).toLocaleString()}</span>
              <p className="text-gray-200 mt-1">{comment.comment}</p>
            </div>
            {task.user?.role !== 'user' && (
              <div className="relative">
                <button
                  className="text-gray-400 hover:text-red-500 px-2 py-1 rounded focus:outline-none"
                  onClick={() => setDropdownOpenId(dropdownOpenId === comment.id ? null : comment.id)}
                >
                  <i className="fas fa-ellipsis-v"></i>
                </button>
                {dropdownOpenId === comment.id && (
                  <div className="absolute right-0 mt-2 w-32 bg-gray-800 rounded shadow-lg z-50">
                    <button
                      onClick={() => handleCommentDelete(comment.id)}
                      className="block w-full text-left px-4 py-2 text-red-500 hover:bg-gray-700 rounded"
                    >
                      Delete
                    </button>
                  </div>
                )}
              </div>
            )}
          </div>
        ))}
      </div>

      <div className="rounded-lg shadow-md p-6 mb-8" style={{ background: '#161f30' }}>
        <form onSubmit={handleCommentSubmit}>
          <div className="mb-4">
            <label className="block text-gray-400 font-medium mb-1">Add a Comment</label>
            <textarea
              value={comment}
              onChange={(e) => setComment(e.target.value)}
              required
              placeholder="Write a comment..."
              className="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
              rows={3}
              style={{ background: '#1a2238' }}
            />
          </div>
          <button type="submit" className="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2">
            Add Comment
          </button>
        </form>
      </div>
    </div>
  );
};

export default TaskDetail;
