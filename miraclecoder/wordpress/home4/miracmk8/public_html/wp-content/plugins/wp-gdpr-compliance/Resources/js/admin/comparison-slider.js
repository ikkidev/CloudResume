/**
 * Comparison slider
 */
export default class ComparisonSlider {
    constructor () {
        this.setProperties()
        this.init()
    }

    setProperties () {
        this.slider = document.querySelector('.wpgdprc-comparison-slider')
        this.topSlide = document.querySelector('.wpgdprc-comparison-slider__top')
        this.line = document.querySelector('.wpgdprc-comparison-slider__line')
    }

    init () {
        if (!this.slider) return

        this.setInitialPosition()

        this.slider.addEventListener('mousemove', (e) => {
            const rect = this.slider.getBoundingClientRect()
            const x = e.clientX - rect.left

            this.setOffsets(x)
        })

        window.addEventListener('resize', () => {
            this.setInitialPosition()
        })
    }

    setOffsets (x) {
        this.topSlide.style.clip = `rect(auto, ${x}px, auto, auto)`

        let offset = x - this.line.getBoundingClientRect().width / 2

        if (offset < 0) {
            offset = 0
        }

        const maxWidth = this.topSlide.getBoundingClientRect().width
        if (offset > maxWidth) {
            offset = maxWidth
        }

        this.line.style.left = `${offset >= 0 ? offset : 0}px`
    }

    setInitialPosition () {
        this.setOffsets(this.slider.getBoundingClientRect().width / 2)
    }
}
