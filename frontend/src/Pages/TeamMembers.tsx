import React, { useEffect, useState, type FormEvent } from 'react';
import Header from '../Components/Header';
import axios from '../axios'; 
import Swal from 'sweetalert2';
import { motion } from 'framer-motion';
import { useAuth } from "@clerk/clerk-react";

interface TeamMember {
  id: number;
  name: string;
  email: string;
}

const TeamMembers: React.FC = () => {
  const { getToken } = useAuth();
  const [isLoading, setIsLoading] = useState(true);
  const [members, setMembers] = useState<TeamMember[]>([]);
  const [modalOpen, setModalOpen] = useState(false);
  const [deleteModalOpen, setDeleteModalOpen] = useState(false);
  const [currentMember, setCurrentMember] = useState<Partial<TeamMember>>({});
  const [isEditMode, setIsEditMode] = useState(false);
  const [deleteTarget, setDeleteTarget] = useState<Partial<TeamMember>>({});

  useEffect(() => {
    fetchMembers();
  }, []);

  const fetchMembers = async () => {
    setIsLoading(true);
    const token = await getToken();
    if (!token) return;
    try {
      const res = await axios.get<TeamMember[]>('/users', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      setMembers(res.data);
    } catch (error) {
      console.error('Error fetching team members:', error);
    } finally {
      setIsLoading(false);
    }
    
  };

  const openAddModal = () => {
    setCurrentMember({});
    setIsEditMode(false);
    setModalOpen(true);
  };

  const openEditModal = (member: TeamMember) => {
    setCurrentMember(member);
    setIsEditMode(true);
    setModalOpen(true);
  };

  const handleModalClose = () => {
    setModalOpen(false);
  };

  const handleDeleteModal = (member: TeamMember) => {
    setDeleteTarget(member);
    setDeleteModalOpen(true);
  };

  const handleDelete = async () => {
    const token = await getToken();
    if (!token) return;
    try {
      await axios.delete(`/users/${deleteTarget.id}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      Swal.fire({
        icon: 'success',
        title: 'User deleted successfully',
        timer: 1500,
        showConfirmButton: false,
      });
      setDeleteModalOpen(false);
      fetchMembers();
   
    } catch (error) {
   
      let errorMessage = 'Something went wrong';
      if (typeof error === 'object' && error !== null && 'response' in error) {
        const err = error as { response?: { data?: { message?: string } } };
        errorMessage = err.response?.data?.message || errorMessage;
      }
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errorMessage,
      });
   
    } 
  };


  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    const method = isEditMode ? 'put' : 'post';
    const url = isEditMode ? `/users/${currentMember.id}` : '/users';
    const token = await getToken();
    if (!token) return;

    try {
      await axios[method](url, currentMember, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      Swal.fire({
        icon: 'success',
        title: 'User saved successfully!',
        timer: 1500,
        showConfirmButton: false,
      });
      setModalOpen(false);
      fetchMembers();
    
    } catch (error) {
    
      let errorMessage = 'Something went wrong';
      if (typeof error === 'object' && error !== null && 'response' in error) {
        const err = error as { response?: { data?: { message?: string } } };
        errorMessage = err.response?.data?.message || errorMessage;
      }
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errorMessage,
      });
    
    }
  };

  return (
    <>
      <Header isHome={false} />

      <div className="container mx-auto text-white px-6 py-8" style={{ paddingTop : '160px' }}>
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0 }}   
          className="flex flex-col md:flex-row justify-between items-center mb-6 border-b dark:border-blue-500 pb-4">
          <h2 className="text-3xl font-semibold">Team Members</h2>
          <button
            className="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2 mt-4 md:mt-0 cursor-pointer"
            onClick={openAddModal}
          >
            Add Team Member
          </button>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.2 }}   
          className="relative overflow-x-auto rounded-lg shadow">
          <table className="w-full text-sm text-center text-gray-300">
            <thead className="text-xs uppercase bg-gray-900 text-gray-400">
              <tr>
                <th className="px-6 py-3">Name</th>
                <th className="px-6 py-3">Email</th>
                <th className="px-6 py-3">Actions</th>
              </tr>
            </thead>
            <tbody className='divide-y' style={{ background: '#0c1220' }}>
              {isLoading ? (
                <tr>
                  <td colSpan={3}>
                    <div className="flex justify-center items-center py-8">
                      <div className="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                      <span className="ml-2 text-gray-500">Loading...</span>
                    </div>
                  </td>
                </tr>
              ) : members.length === 0 ? (
                <tr>
                  <td colSpan={3} className="py-6 text-gray-500">
                    No team member found.
                  </td>
                </tr>
              ) : (
                members.map((member) => (
                  <tr key={member.id} className="border-b">
                    <td className="px-6 py-4">{member.name}</td>
                    <td>{member.email}</td>
                    <td>
                      <div className="inline-flex shadow-sm py-4 px-3" role="group">
                        <button
                          className="px-4 py-2 text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded-s-lg"
                          onClick={() => openEditModal(member)}
                        >
                          Edit
                        </button>
                        <button
                          className="px-4 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-e-lg"
                          onClick={() => handleDeleteModal(member)}
                        >
                          Delete
                        </button>
                      </div>
                    </td>
                  </tr>
                ))
              )}                
            </tbody>
          </table>
        </motion.div>

        {/* Add/Edit Modal */}
        {modalOpen && (
          <div
            className="fixed inset-0 z-50 backdrop-blur-sm flex justify-center items-center px-4 py-10"
            onClick={(e) => e.target === e.currentTarget && handleModalClose()}
          >
            <div className="text-white bg-[#161f30] rounded-xl shadow-2xl w-full max-w-xl">
              <div className="flex justify-between items-center rounded-xs border-b border-blue-700 px-6 py-4">
                <h5 className="text-2xl font-semibold">{isEditMode ? 'Edit Team Member' : 'Add Team Member'}</h5>
                <button className="text-blue-400 hover:text-red-600 text-2xl font-bold cursor-pointer" onClick={handleModalClose}>&times;</button>
              </div>
             
              <form className="p-6 space-y-4" onSubmit={handleSubmit}>
                <div>
                  <label className="block text-gray-400 mb-1">Name</label>
                  <input
                    type="text"
                    value={currentMember.name || ''}
                    onChange={(e) => setCurrentMember({ ...currentMember, name: e.target.value })}
                    className="w-full border border-gray-600 rounded-lg px-3 py-2 bg-[#1a2238] focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                  />
                </div>
                <div>
                  <label className="block text-gray-400 mb-1">Email</label>
                  <input
                    type="email"
                    value={currentMember.email || ''}
                    onChange={(e) => setCurrentMember({ ...currentMember, email: e.target.value })}
                    className="w-full border border-gray-600 rounded-lg px-3 py-2 bg-[#1a2238] focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                  />
                </div>
                {!isEditMode && (
                  <div>
                    <label className="block text-gray-400 mb-1">Password</label>
                    <input
                      type="password"
                      min={6}
                      onChange={(e) => setCurrentMember({ ...currentMember, password: e.target.value } as Partial<TeamMember>)}
                      className="w-full border border-gray-600 rounded-lg px-3 py-2 bg-[#1a2238] focus:outline-none focus:ring-2 focus:ring-blue-500"
                      required
                    />
                  </div>
                )}
                <div className="flex justify-center gap-3 pt-4">
                  <button
                    type="button"
                    onClick={handleModalClose}
                    className="bg-gray-200 text-gray-700 font-semibold rounded-lg px-4 py-2 cursor-pointer"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    className="bg-blue-600 hover:bg-blue-800 text-white font-semibold rounded-lg px-4 py-2 cursor-pointer"
                  >
                    Save
                  </button>
                </div>
              </form>
            </div>
          </div>
        )}

        {/* Delete Confirmation Modal */}
        {deleteModalOpen && (
          <div
            className="fixed inset-0 z-50 backdrop-blur-sm flex justify-center items-center px-4 py-10"
            onClick={(e) => e.target === e.currentTarget && setDeleteModalOpen(false)}
          >

            <div className="text-white bg-[#161f30] rounded-xl shadow-2xl w-full max-w-md">
              <div className="flex justify-between items-center rounded-xs border-b border-blue-700 px-6 py-4">
                <h5 className="text-xl font-semibold">Confirm Deletion</h5>
                <button className="text-blue-400 hover:text-red-600 text-2xl font-bold cursor-pointer" onClick={() => setDeleteModalOpen(false)}>&times;</button>
              </div>
              <div className='p-6'>
                <p className="mb-8">
                  Are you sure you want to delete{' '}
                  <strong>{deleteTarget.name}</strong>?
                </p>

                <div className="flex justify-center gap-3">
                  <button
                    className="bg-gray-200 text-gray-700 font-semibold rounded-lg px-4 py-2 cursor-pointer"
                    onClick={() => setDeleteModalOpen(false)}
                  >
                    Cancel
                  </button>
                  <button
                    className="bg-red-600 hover:bg-red-800 text-white font-semibold rounded-lg px-4 py-2 cursor-pointer"
                    onClick={handleDelete}
                  >
                    Yes, Delete
                  </button>
                </div>
              </div>
              
              
            </div>
          </div>
        )}
      </div>
    </>

  );
};

export default TeamMembers;