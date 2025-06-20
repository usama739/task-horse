import React, { useEffect, useState } from "react";
import axios from "axios";
import type { FC, FormEvent } from "react";
import Header from '../Components/Header'

interface Project {
  id: string;
  name: string;
}

function Projects() {
  const [projects, setProjects] = useState<Project[]>([]);
  const [showFormModal, setShowFormModal] = useState<boolean>(false);
  const [showDeleteModal, setShowDeleteModal] = useState<boolean>(false);
  const [projectForm, setProjectForm] = useState({ id: "", name: "", method: "POST" });
  const [deleteTarget, setDeleteTarget] = useState({ id: "", name: "" });

  useEffect(() => {
    fetchProjects();
  }, []);

  const fetchProjects = async () => {
    const res = await axios.get<Project[]>("/api/projects"); // adjust API endpoint if needed
    console.log(res.data)
    setProjects(res.data);
  };

  const openAddProjectModal = () => {
    setProjectForm({ id: "", name: "", method: "POST" });
    setShowFormModal(true);
  };

  const openEditProjectModal = (project: Project) => {
    setProjectForm({ id: project.id, name: project.name, method: "PUT" });
    setShowFormModal(true);
  };

  const openDeleteProjectModal = (project: Project) => {
    setDeleteTarget(project);
    setShowDeleteModal(true);
  };

  const handleProjectFormChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setProjectForm({ ...projectForm, name: e.target.value });
  };

  const handleProjectFormSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const url = projectForm.method === "POST" ? "/api/projects" : `/api/projects/${projectForm.id}`;
    await axios({
      url,
      method: projectForm.method,
      data: { name: projectForm.name },
    });
    setShowFormModal(false);
    fetchProjects();
  };

  const handleDeleteProject = async () => {
    await axios.delete(`/api/projects/${deleteTarget.id}`);
    setShowDeleteModal(false);
    fetchProjects();
  };


  return (
    <>
    <Header isHome={false} />


    <div className="container mx-auto" style={{ paddingTop : '135px' }}>
      <div className="flex flex-col md:flex-row md:justify-between md:items-center mb-6 border-b dark:border-blue-500 pb-4">
        <h4 className="text-3xl font-semibold mb-4 md:mb-0 text-white">Projects</h4>
        <button
          className="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2 transition w-full md:w-auto cursor-pointer"
          onClick={openAddProjectModal}
        >
          Add Project
        </button>
      </div>
      <div className="relative overflow-x-auto rounded-lg shadow">
        <table className="w-full text-sm text-center text-gray-300">
          <thead className="text-xs uppercase bg-gray-900 text-gray-400">
            <tr>
              <th className="px-6 py-3">Name</th>
              <th className="px-6 py-3">Actions</th>
            </tr>
          </thead>

          <tbody style={{ background: "#0c1220" }}>
            {/* {projects.length > 0 && projects.map((project) => (
              <tr key={project.id} className="border-b">
                <td className="px-6 py-4">{project.name}</td>
                <td>
                  <div className="inline-flex shadow-sm py-4">
                    <button
                      className="px-4 py-2 text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded-s-lg"
                      onClick={() => openEditProjectModal(project)}
                    >
                      Edit
                    </button>
                    <button
                      className="px-4 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-e-lg"
                      onClick={() => openDeleteProjectModal(project)}
                    >
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            ))} */}

            {projects.length === 0 && (
              <tr>
                <td colSpan={3} className="py-6 text-gray-500">
                  No projects found.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      {/* Add/Edit Project Modal */}
      {showFormModal && (
        <div className="fixed inset-0 z-50 bg-black bg-opacity-60 backdrop-blur-sm flex justify-center items-center">
          <div className="text-white rounded-xl shadow-2xl w-full max-w-xl" style={{ background: "#161f30" }}>
            <div className="flex justify-between items-center rounded-xs border-b border-blue-700 px-6 py-4">
              <h5 className="text-2xl font-semibold">{projectForm.id ? "Edit Project" : "Add Project"}</h5>
              <button className="text-blue-400 hover:text-red-600 text-2xl font-bold cursor-pointer" onClick={() => setShowFormModal(false)}>&times;</button>
            </div>
            <form className="p-6" onSubmit={handleProjectFormSubmit}>
              <div className="mb-3">
                <label className="block font-medium text-gray-500 mb-1">Project Name</label>
                <input
                  type="text"
                  className="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                  style={{ background: "#1a2238" }}
                  value={projectForm.name}
                  onChange={handleProjectFormChange}
                  required
                />
              </div>
              <div className="flex justify-center gap-2 pt-4">
                <button
                  type="button"
                  className="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-4 py-2 cursor-pointer"
                  onClick={() => setShowFormModal(false)}
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
      {showDeleteModal && (
        <div className="fixed inset-0 z-50 bg-black bg-opacity-60 backdrop-blur-sm flex justify-center items-center">
          <div className="text-white rounded-xl shadow-2xl w-full max-w-md p-6" style={{ background: "#161f30" }}>
            <div className="flex justify-between border-b border-blue-700 mb-4">
              <h5 className="text-xl font-semibold">Confirm Deletion</h5>
              <button className="text-blue-400 hover:text-red-600 text-2xl font-bold" onClick={() => setShowDeleteModal(false)}>&times;</button>
            </div>
            <p className="mb-6">Are you sure you want to delete <strong>{deleteTarget.name}</strong>?</p>
            <div className="flex justify-center gap-2">
              <button
                className="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-4 py-2"
                onClick={() => setShowDeleteModal(false)}
              >
                Cancel
              </button>
              <button
                className="bg-red-600 hover:bg-red-800 text-white font-semibold rounded-lg px-4 py-2"
                onClick={handleDeleteProject}
              >
                Yes, Delete
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
    </>
  )
}

export default Projects