import { computed, isRef } from 'vue'
export const useMonthlyPayment =(total, interestRate, duration) => {
  const monthlyPayment = computed(() => {
    const principal = isRef(total) ? total.value : total
    const monthlyInterestRate = isRef(interestRate) ? interestRate.value / 100 / 12 : interestRate / 100 / 12
    const numberOfPayments = isRef(duration) ? duration.value * 12 : duration * 12

    if (monthlyInterestRate === 0) {
      return principal / numberOfPayments
    }

    return principal * monthlyInterestRate / (1 - Math.pow(1 + monthlyInterestRate, -numberOfPayments))
  })
  const totalPaid = computed(() => monthlyPayment.value * (isRef(duration) ? duration.value * 12 : duration * 12))
  const totalInterest = computed(() => totalPaid.value - (isRef(total) ? total.value : total))
  return { monthlyPayment, totalPaid, totalInterest }
}