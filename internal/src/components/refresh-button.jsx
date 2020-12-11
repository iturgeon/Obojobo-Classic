import './refresh-button.scss'
import RefreshIcon from './refresh-icon'
import React from 'react'
import PropTypes from 'prop-types'

export default function RefreshButton({ onClick }) {
	return (
		<button onClick={onClick} className="refresh-button" title="refresh">
			<RefreshIcon />
		</button>
	)
}

RefreshButton.propTypes = {
	onClick: PropTypes.func.isRequired
}
