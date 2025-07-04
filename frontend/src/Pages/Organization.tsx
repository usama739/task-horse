import React, { useState } from "react";
import { useUser } from "@clerk/clerk-react";
import axios from "axios";
import { useNavigate } from "react-router-dom";

const CreateOrganization: React.FC = () => {
  const { user } = useUser();
  const [name, setName] = useState("");
  const navigate = useNavigate();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    try {
      await axios.post("/api/organizations", {
        name,
        clerk_id: user?.id, // Send this to associate org with admin
      });

      navigate("/projects"); 
    } catch (error) {
      console.error("Failed to create organization", error);
    }
  };

  return (
    <div className="h-screen flex items-center justify-center">
      <form
        onSubmit={handleSubmit}
        className="bg-[#161f30] p-8 rounded-lg shadow-md w-full max-w-md"
      >
        <h2 className="text-2xl font-semibold mb-6 text-center text-white">Create Organization</h2>
        <input
          type="text"
          placeholder="Organization Name"
          value={name}
          onChange={(e) => setName(e.target.value)}
          required
          className="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-white mb-5"
        />
        <button
          type="submit"
          className="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2"
        >
          Create
        </button>
      </form>
    </div>
  );
};

export default CreateOrganization;
