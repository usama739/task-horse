import { CircularProgressbar, buildStyles } from 'react-circular-progressbar';

type Props = {
  label: string;
  value: number;
  color: string;
};

const PriorityProgress: React.FC<Props> = ({ label, value, color }) => {
  return (
    <div className="bg-[#1f2937] p-6 rounded-xl shadow-md text-white flex flex-col items-center border border-blue-800">
      <div className="w-24 h-24 mb-4">
        <CircularProgressbar
          value={value}
          text={`${value}`}
          styles={buildStyles({
            textColor: 'white',
            pathColor: color,
            trailColor: '#374151',
          })}
        />
      </div>
      <h4 className="text-sm text-gray-300">{label} Priority</h4>
    </div>
  );
};


export default PriorityProgress;