import {
  CircularProgressbar,
  buildStyles,
} from 'react-circular-progressbar';
import 'react-circular-progressbar/dist/styles.css';
import CountUp from 'react-countup';
import { motion } from 'framer-motion';

interface PriorityProps {
  high: number;
  medium: number;
  low: number;
}

const PriorityProgressBars: React.FC<{ counts: PriorityProps }> = ({ counts }) => {
  const total = counts.high + counts.medium + counts.low;

  const getPercentage = (count: number) =>
    total > 0 ? Math.round((count / total) * 100) : 0;

  return (
    <div className="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
      {/* High Priority */}
      <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.4 }}
          className="bg-[#161f30] p-6 rounded-xl shadow flex flex-col items-center text-white border border-blue-800">
        <div className="w-24 h-24 mb-2">
          <CircularProgressbar
            value={getPercentage(counts.high)}
            text={`${getPercentage(counts.high)}%`}
            styles={buildStyles({
              textColor: '#fff',
              pathColor: '#ef4444',
              trailColor: '#374151',
            })}
          />
        </div>
        <p className="text-md font-semibold mt-2">High Priority</p>
        <p className="text-xl font-bold text-red-500">
          <CountUp end={counts.high} duration={1.5} />
        </p>
      </motion.div>

      {/* Medium Priority */}
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ delay: 0.4 }} 
        className="bg-[#161f30] p-6 rounded-xl shadow flex flex-col items-center text-white border border-blue-800">
        <div className="w-24 h-24 mb-2">
          <CircularProgressbar
            value={getPercentage(counts.medium)}
            text={`${getPercentage(counts.medium)}%`}
            styles={buildStyles({
              textColor: '#fff',
              pathColor: '#facc15',
              trailColor: '#374151',
            })}
          />
        </div>
        <p className="text-md font-semibold mt-2">Medium Priority</p>
        <p className="text-xl font-bold text-yellow-400">
          <CountUp end={counts.medium} duration={1.5} />
        </p>
      </motion.div>

      {/* Low Priority */}
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ delay: 0.4 }}
        className="bg-[#161f30] p-6 rounded-xl shadow flex flex-col items-center text-white border border-blue-800">
        <div className="w-24 h-24 mb-2">
          <CircularProgressbar
            value={getPercentage(counts.low)}
            text={`${getPercentage(counts.low)}%`}
            styles={buildStyles({
              textColor: '#fff',
              pathColor: '#22c55e',
              trailColor: '#374151',
            })}
          />
        </div>
        <p className="text-md font-semibold mt-2">Low Priority</p>
        <p className="text-xl font-bold text-green-400">
          <CountUp end={counts.low} duration={1.5} />
        </p>
      </motion.div>
    </div>
  );
};

export default PriorityProgressBars;