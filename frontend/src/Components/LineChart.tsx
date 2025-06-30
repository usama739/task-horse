import React, { useEffect, useState } from 'react';
import { Line } from 'react-chartjs-2';
import axios from '../axios';
import {
  Chart as ChartJS,
  LineElement,
  PointElement,
  CategoryScale,
  LinearScale,
  Title,
  Tooltip,
  Legend,
} from 'chart.js';
import dayjs from 'dayjs';

ChartJS.register(LineElement, PointElement, CategoryScale, LinearScale, Title, Tooltip, Legend);

interface LineChartProps {
  startDate?: Date;
  endDate?: Date;
  projectId?: string;
  status?: string;
}

const LineChart: React.FC<LineChartProps> = ({ startDate, endDate, projectId, status }) => {
  const [chartData, setChartData] = useState<any>(null);

  useEffect(() => {
    console.log('Fetching data for LineChart', { startDate, endDate, projectId, status });
    const fetchData = async () => {
      const res = await axios.get('/dashboard/task-status-trend'
        , {
          params: {
            start_date: startDate ? dayjs(startDate).format("YYYY-MM-DD") : undefined,
            end_date: endDate ? dayjs(endDate).format("YYYY-MM-DD") : undefined,
            project_id: projectId,
            status: status,
          }
        }
      );
      const data = res.data as Record<string, { Created: number; 'In-Progress': number; Completed: number }>;

      const labels = Object.keys(data); // Last 30 days

      const created = labels.map(day => data[day]['Created']);
      const inProgress = labels.map(day => data[day]['In-Progress']);
      const completed = labels.map(day => data[day]['Completed']);

      setChartData({
        labels,
        datasets: [
          {
            label: 'Created',
            data: created,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.2)',
            fill: true,
            tension: 0.4,
          },
          {
            label: 'In Progress',
            data: inProgress,
            borderColor: '#facc15',
            backgroundColor: 'rgba(250,204,21,0.2)',
            fill: true,
            tension: 0.4,
          },
          {
            label: 'Completed',
            data: completed,
            borderColor: '#22d3ee',
            backgroundColor: 'rgba(34,211,238,0.2)',
            fill: true,
            tension: 0.4,
          }
        ]
      });
    };

    fetchData();
  }, [startDate, endDate, projectId, status]);

  const options = {
    responsive: true,
    plugins: {
      legend: {
        labels: { color: '#cbd5e1' },
      },
    },
    scales: {
      x: {
        ticks: { color: '#cbd5e1' },
        grid: { color: '#1e293b' },
      },
      y: {
        ticks: { color: '#cbd5e1' },
        grid: { color: '#1e293b' },
      }
    }
  };

  return (
    <div className="p-6 flex flex-col">
      {chartData ? (
        <Line data={chartData} options={options} />
      ) : (
        <div className="flex justify-center items-center py-8">
            <div className="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
            <span className="ml-2 text-gray-500">Loading...</span>
        </div>
      )}
    </div>
  );
};

export default LineChart;
