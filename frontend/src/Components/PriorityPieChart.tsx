import { useEffect, useState } from 'react';
import { Chart as ChartJS, PieController, ArcElement, Tooltip, Legend } from 'chart.js';
import { Pie } from 'react-chartjs-2';
import axios from '../axios'; 
import dayjs from 'dayjs';

ChartJS.register(PieController, ArcElement, Tooltip, Legend);

interface PieChartProps {
  startDate?: Date;
  endDate?: Date;
  projectId?: string;
  status?: string;
}

const PriorityPieChart: React.FC<PieChartProps> = ({ startDate, endDate, projectId, status }) => {
  const [priorityData, setPriorityData] = useState<{ priority: string; count: number }[]>([]);

  useEffect(() => {
    axios.get<any>('/dashboard/tasks-by-priority', {
      params: {
        start_date: startDate ? dayjs(startDate).format("YYYY-MM-DD") : undefined,
        end_date: endDate ? dayjs(endDate).format("YYYY-MM-DD") : undefined,
        project_id: projectId,
        status: status,
      }
    }).then((res) => {
      setPriorityData(res.data);
    });
  }, [startDate, endDate, projectId, status]);

  const chartData = {
    labels: priorityData.map((item) => item.priority),
    datasets: [
      {
        data: priorityData.map((item) => item.count),
        backgroundColor: ['#ef4444', '#facc15', '#10b981'], // red, yellow, green
        borderColor: '#161f30',
        borderWidth: 2,
      },
    ],
  };

  const chartOptions = {
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
        {priorityData ? (
            <Pie data={chartData} options={chartOptions} />
        ) : (
            <div className="flex justify-center items-center py-8">
                <div className="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                <span className="ml-2 text-gray-500">Loading...</span>
            </div>
        )}
    </div>
  );
};

export default PriorityPieChart;
