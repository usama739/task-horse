import { useEffect, useState } from 'react';
import { Chart as ChartJS, DoughnutController, ArcElement, Tooltip, Legend } from 'chart.js';
import { Doughnut } from 'react-chartjs-2';
import axios from '../axios'; // adjust path if needed
import dayjs from 'dayjs';
import { useAuth } from '@clerk/clerk-react';

ChartJS.register(DoughnutController, ArcElement, Tooltip, Legend);

interface DonutChartProps {
  startDate?: Date;
  endDate?: Date;
  projectId?: string;
  status?: string;
}

const ProjectDonutChart: React.FC<DonutChartProps> = ({ startDate, endDate, projectId, status }) => {
  const [data, setData] = useState<{ project: string; count: number }[]>([]);
  const [loading, setLoading] = useState(true);
  const { getToken } = useAuth();

  useEffect(() => {
    const fetchDonutChart = async () => {
      setLoading(true);
      const token = await getToken();
      if (!token) return;

      axios.get<any>('/dashboard/tasks-by-project', {
        params: {
          start_date: startDate ? dayjs(startDate).format("YYYY-MM-DD") : undefined,
          end_date: endDate ? dayjs(endDate).format("YYYY-MM-DD") : undefined,
          project_id: projectId,
          status: status,
        },
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }).then((res) => {
        setData(res.data);
        setLoading(false);
      });
    }
    
    fetchDonutChart();
  }, [startDate, endDate, projectId, status]);

  const chartData = {
    labels: data.map((item) => item.project),
    datasets: [
      {
        data: data.map((item) => item.count),
        backgroundColor: [
          '#38bdf8',
          '#34d399',
          '#f472b6',
          '#facc15',
          '#a78bfa',
          '#f97316',
          '#ec4899',
        ],
        borderColor: '#161f30',
        borderWidth: 2,
      },
    ],
  };

  const chartOptions = {
    cutout: '60%',
    plugins: {
      legend: {
        labels: {
          color: '#cbd5e1',
          font: { size: 14 },
        },
      },
    },
  };

  return (
    <div className="p-6 flex flex-col">
        {!loading ? (
            <Doughnut data={chartData} options={chartOptions} />
        ) : (
            <div className="flex justify-center items-center py-8">
                <div className="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                <span className="ml-2 text-gray-500">Fetching latest data, please wait...</span>
            </div>
        )}
    </div>
  );
};

export default ProjectDonutChart;
