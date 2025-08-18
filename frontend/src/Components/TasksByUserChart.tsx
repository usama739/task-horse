import { useEffect, useState } from 'react';
import { Bar } from 'react-chartjs-2';
import axios from '../axios'; 
import dayjs from 'dayjs';
import { Chart as ChartJS, BarElement, CategoryScale, LinearScale, Tooltip, Legend } from 'chart.js';
import type { ChartData } from 'chart.js';
import { useAuth } from '@clerk/clerk-react';

ChartJS.register(BarElement, CategoryScale, LinearScale, Tooltip, Legend);

interface UserChartProps {
  startDate?: Date;
  endDate?: Date;
  projectId?: string;
  status?: string;
}

interface UserData {
  id: string;
  name: string;
  completed_tasks_count: number;
}


const TasksByUserChart: React.FC<UserChartProps> = ({ startDate, endDate, projectId, status }) => {
  const [chartData, setChartData] = useState<ChartData<'bar'> | null>(null);
  const { getToken } = useAuth();

  useEffect(() => {
    const fetchUserChart = async () => {
      const token = await getToken();
      if (!token) return;

      axios.get<UserData[]>('/dashboard/completed-tasks-by-user', {
          params: {
              start_date: startDate ? dayjs(startDate).format("YYYY-MM-DD") : undefined,
              end_date: endDate ? dayjs(endDate).format("YYYY-MM-DD") : undefined,
              project_id: projectId,
              status: status,
              },
              headers: {
                Authorization: `Bearer ${token}`,
              },
          }
      ).then(res => {
        const users = res.data;
        const labels = users.map((u) => u.name);
        const data = users.map((u) => u.completed_tasks_count);

        setChartData({
          labels,
          datasets: [
            {
              label: 'Completed Tasks',
              data,
              backgroundColor: ['#3b82f6', '#22d3ee', '#facc15', '#38bdf8', '#a78bfa'],
              borderRadius: 8,
            }
          ]
        });
      });
    }

    fetchUserChart();
  }, [startDate, endDate, projectId, status]);


  return (
    <div className="p-6 flex flex-col">
        {chartData ? (
            <Bar
                data={chartData}
                options={{
                plugins: {
                    legend: { display: false },
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
                }}
            />
            ) : (
            <div className="flex justify-center items-center py-8 h-full">
                <div className="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                <span className="ml-2 text-gray-500">Fetching latest data, please wait...</span>
            </div>
            )
        }
    </div>
  );
};

export default TasksByUserChart;
