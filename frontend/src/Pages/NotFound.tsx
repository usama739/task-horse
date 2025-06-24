import React from 'react';
import { Link } from 'react-router-dom';

const NotFound: React.FC = () => {
  return (
    <div className="flex items-center justify-center h-screen bg-[#0c1220] text-white">
      <div className="text-center">
        <h1 className="text-6xl font-bold mb-4">404</h1>
        <h2 className="text-2xl font-semibold mb-2">Page Not Found</h2>
        <p className="mb-6 text-gray-400">The page you're looking for doesn't exist.</p>
        <Link
          to="/"
          className="bg-blue-600 hover:bg-blue-800 text-white px-6 py-2 rounded transition"
        >
          Go Home
        </Link>
      </div>
    </div>
  );
};

export default NotFound;
